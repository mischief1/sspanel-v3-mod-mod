<?php
/* vim: set expandtab sw=4 ts=4 sts=4: */
/**
 * Shared code for mysql charsets
 *
 * @package PhpMyAdmin
 */
if (! defined('PHPMYADMIN')) {
    exit;
}

/**
 * Generate charset dropdown box
 *
 * @param int         $type           Type
 * @param string      $name           Element name
 * @param string      $id             Element id
 * @param null|string $default        Default value
 * @param bool        $label          Label
 * @param bool        $submitOnChange Submit on change
 *
 * @return string
 */
function PMA_generateCharsetDropdownBox($type = PMA_CSDROPDOWN_COLLATION,
    $name = null, $id = null, $default = null, $label = true,
    $submitOnChange = false
) {
    global $mysql_charsets, $mysql_charsets_descriptions,
        $mysql_charsets_available, $mysql_collations, $mysql_collations_available;

    if (empty($name)) {
        if ($type == PMA_CSDROPDOWN_COLLATION) {
            $name = 'collation';
        } else {
            $name = 'character_set';
        }
    }

    $return_str  = '<select lang="en" dir="ltr" name="'
        . htmlspecialchars($name) . '"'
        . (empty($id) ? '' : ' id="' . htmlspecialchars($id) . '"')
        . ($submitOnChange ? ' class="autosubmit"' : '') . '>' . "\n";
    if ($label) {
        $return_str .= '<option value="">'
            . ($type == PMA_CSDROPDOWN_COLLATION ? __('Collation') : __('Charset'))
            . '</option>' . "\n";
    }
    $return_str .= '<option value=""></option>' . "\n";
    foreach ($mysql_charsets as $current_charset) {
        if (!$mysql_charsets_available[$current_charset]) {
            continue;
        }
        $current_cs_descr
            = empty($mysql_charsets_descriptions[$current_charset])
            ? $current_charset
            : $mysql_charsets_descriptions[$current_charset];

        if ($type == PMA_CSDROPDOWN_COLLATION) {
            $return_str .= '<optgroup label="' . $current_charset
                . '" title="' . $current_cs_descr . '">' . "\n";
            foreach ($mysql_collations[$current_charset] as $current_collation) {
                if (!$mysql_collations_available[$current_collation]) {
                    continue;
                }
                $return_str .= '<option value="' . $current_collation
                    . '" title="' . PMA_getCollationDescr($current_collation) . '"'
                    . ($default == $current_collation ? ' selected="selected"' : '')
                    . '>'
                    . $current_collation . '</option>' . "\n";
            }
            $return_str .= '</optgroup>' . "\n";
        } else {
            $return_str .= '<option value="' . $current_charset
                . '" title="' . $current_cs_descr . '"'
                . ($default == $current_charset ? ' selected="selected"' : '') . '>'
                . $current_charset . '</option>' . "\n";
        }
    }
    $return_str .= '</select>' . "\n";

    return $return_str;
}

/**
 * Generate the charset query part
 *
 * @param string $collation Collation
 *
 * @return string
 */
function PMA_generateCharsetQueryPart($collation)
{
    if (!PMA_DRIZZLE) {
        list($charset) = explode('_', $collation);
        return ' CHARACTER SET ' . $charset
            . ($charset == $collation ? '' : ' COLLATE ' . $collation);
    } else {
        return ' COLLATE ' . $collation;
    }
}

/**
 * returns collation of given db
 *
 * @param string $db name of db
 *
 * @return string  collation of $db
 */
function PMA_getDbCollation($db)
{
    if ($GLOBALS['dbi']->isSystemSchema($db)) {
        // We don't have to check the collation of the virtual
        // information_schema database: We know it!
        return 'utf8_general_ci';
    }

    if (! $GLOBALS['cfg']['Server']['DisableIS']) {
        // this is slow with thousands of databases
        $sql = PMA_DRIZZLE
            ? 'SELECT DEFAULT_COLLATION_NAME FROM data_dictionary.SCHEMAS'
            . ' WHERE SCHEMA_NAME = \'' . PMA_Util::sqlAddSlashes($db)
            . '\' LIMIT 1'
            : 'SELECT DEFAULT_COLLATION_NAME FROM information_schema.SCHEMATA'
            . ' WHERE SCHEMA_NAME = \'' . PMA_Util::sqlAddSlashes($db)
            . '\' LIMIT 1';
        return $GLOBALS['dbi']->fetchValue($sql);
    } else {
        $GLOBALS['dbi']->selectDb($db);
        $return = $GLOBALS['dbi']->fetchValue(
            'SHOW VARIABLES LIKE \'collation_database\'', 0, 1
        );
        if ($db !== $GLOBALS['db']) {
            $GLOBALS['dbi']->selectDb($GLOBALS['db']);
        }
        return $return;
    }
}

/**
 * returns default server collation from show variables
 *
 * @return string  $server_collation
 */
function PMA_getServerCollation()
{
    return $GLOBALS['dbi']->fetchValue(
        'SHOW VARIABLES LIKE \'collation_server\'', 0, 1
    );
}

/**
 * returns description for given collation
 *
 * @param string $collation MySQL collation string
 *
 * @return string  collation description
 */
function PMA_getCollationDescr($collation)
{
    if ($collation == 'binary') {
        return __('Binary');
    }
    $parts = explode('_', $collation);
    if (count($parts) == 1) {
        $parts[1] = 'general';
    } elseif ($parts[1] == 'ci' || $parts[1] == 'cs') {
        $parts[2] = $parts[1];
        $parts[1] = 'general';
    }
    $descr = '';
    switch ($parts[1]) {
    case 'bulgarian':
        $descr = __('Bulgarian');
        break;
    case 'chinese':
        if ($parts[0] == 'gb2312' || $parts[0] == 'gbk') {
            $descr = __('Simplified Chinese');
        } elseif ($parts[0] == 'big5') {
            