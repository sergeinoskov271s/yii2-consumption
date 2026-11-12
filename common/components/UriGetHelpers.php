<?php


namespace app\common\components;


class UriGetHelpers
{
    /**
     * @param $org_unit_mvz
     * @return mixed|null
     */
    public static function getSectionUri($org_unit_mvz)
    {
        return (substr($org_unit_mvz,4,2) !== '00' && substr($org_unit_mvz,6,2) !== '00') ?
            $org_unit_mvz : null;
    }

    /**
     * @param $org_unit_mvz
     * @return string|null
     */
    public static function getOfficeUri($org_unit_mvz): ?string
    {
        switch ($org_unit_mvz)
        {
            case substr($org_unit_mvz, 4, 2) === '00' && substr($org_unit_mvz, 6, 2) !== '00':
                $of_uri = substr($org_unit_mvz, 0, 4) . substr($org_unit_mvz, 6, 2);
                break;
            case substr($org_unit_mvz, 4, 2) !== '00':
                $of_uri = substr($org_unit_mvz, 0, 6);
                break;
            default:
                $of_uri = null;
                break;
        }
        return $of_uri;

    }

    /**
     * @param $org_unit_mvz
     * @return false|string
     */
    public static function getDepartmentUri($org_unit_mvz)
    {
        return substr($org_unit_mvz, 0, 4);
    }

    /**
     * @param $org_unit_mvz
     * @return false|string
     */
    public static function getEnterpriseUri($org_unit_mvz)
    {
        return substr($org_unit_mvz, 0, 2);
    }
}