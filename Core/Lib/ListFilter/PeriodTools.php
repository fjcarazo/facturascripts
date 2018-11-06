<?php
/**
 * This file is part of FacturaScripts
 * Copyright (C) 2017-2018 Carlos Garcia Gomez <carlos@facturascripts.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */
namespace FacturaScripts\Core\Lib\ListFilter;

use FacturaScripts\Core\Base\Translator;

/**
 * PeriodTools give us some basic and common methods for periods.
 *
 * @author Artex Trading sa <jcuello@artextrading.com>
 */
class PeriodTools
{

    /**
     * Returns a date applying to the date reported
     * or to the current date (if no date is reported)
     * a relative date format.
     *
     * @param string $format        Relative date format (+1 day)
     * @param string $dateformat    Return date format (d-m-Y)
     * @param string $date          Date to apply relative format
     * @return string
     */
    public static function applyFormatToDate($format, $dateformat = 'd-m-Y', $date = ''): string
    {
        $time = empty($date) ? time() : strtotime($date);
        return date($dateformat, strtotime($format, $time));
    }

    /**
     * Applies on the indicated start and end date
     * the relative formats reported from the current date
     *
     * @param string $startdate
     * @param string $enddate
     * @param string $startformat
     * @param string $endformat
     * @param string $dateformat
     */
    public static function applyFormatToPeriod(&$startdate, &$enddate, $startformat, $endformat, $dateformat = 'd-m-Y')
    {
        $startdate = self::applyFormatToDate($startformat, $dateformat);
        $enddate = self::applyFormatToDate($endformat, $dateformat);
    }

    /**
     * Applies on the start and end date indicated
     * the relative format corresponding to the period indicated
     * starting from the current date
     *
     * @param string $period
     * @param string $startdate
     * @param string $enddate
     * @param string $dateformat
     */
    public static function applyPeriod($period, &$startdate, &$enddate, $dateformat = 'd-m-Y')
    {
        switch ($period) {
            case 'today':
                self::applyFormatToPeriod($startdate, $enddate, 'today', 'today');
                break;

            case 'yesterday':
                self::applyFormatToPeriod($startdate, $enddate, 'yesterday', 'yesterday');
                break;

            case 'this-week':
                self::applyFormatToPeriod($startdate, $enddate, 'mon this week', 'sun this week');
                break;

            case 'this-month':
                self::applyFormatToPeriod($startdate, $enddate, 'first day of', 'last day of');
                break;

            case 'this-year':
                self::applyFormatToPeriod($startdate, $enddate, 'first day of january', 'last day of december');
                break;

            case 'this-last-week':
                self::applyFormatToPeriod($startdate, $enddate, '-7 day', '-1 day');
                break;

            case 'this-last-fortnight':
                self::applyFormatToPeriod($startdate, $enddate, '-14 day', '-1 day');
                break;

            case 'this-last-month':
                self::applyFormatToPeriod($startdate, $enddate, '-1 month', '-1 day');
                break;

            case 'this-last-year':
                self::applyFormatToPeriod($startdate, $enddate, '-1 year', '-1 day');
                break;

            case 'last-week':
                self::applyFormatToPeriod($startdate, $enddate, 'mon last week', 'sun last week');
                break;

            case 'last-month':
                self::applyFormatToPeriod($startdate, $enddate, 'first day of last month', 'last day of last month');
                break;

            case 'last-bimester':
                $date = self::applyFormatToDate('first day of');
                $startdate = self::applyFormatToDate('-2 month', $dateformat, $date);
                $enddate = self::applyFormatToDate('-1 day', $dateformat, $date);
                break;

            case 'last-trimester':
                $date = self::applyFormatToDate('first day of');
                $startdate = self::applyFormatToDate('-3 month', $dateformat, $date);
                $enddate = self::applyFormatToDate('-1 day', $dateformat, $date);
                break;

            case 'last-quarter':
                $date = self::applyFormatToDate('first day of');
                $startdate = self::applyFormatToDate('-4 month', $dateformat, $date);
                $enddate = self::applyFormatToDate('-1 day', $dateformat, $date);
                break;

            case 'last-semester':
                $date = self::applyFormatToDate('first day of');
                $startdate = self::applyFormatToDate('-6 month', $dateformat, $date);
                $enddate = self::applyFormatToDate('-1 day', $dateformat, $date);
                break;

            case 'last-year':
                self::applyFormatToPeriod($startdate, $enddate, 'first day of january last year', 'last day of december last year');
        }
    }

    /**
     * Return list of periods for select base filter
     *
     * @param Translator $i18n
     *
     * @return array
     */
    public static function getFilterOptions(&$i18n)
    {
        $result = [
            ['code' => '', 'description' => '------']
        ];
        foreach (self::getPeriods() as $value) {
            $result[] = ['code' => $value, 'description' => $i18n->trans($value)];
        }
        return $result;
    }

    /**
     * Return list of available periods
     *
     * @return array
     */
    protected static function getPeriods(): array
    {
        return [
            'today',
            'yesterday',
            'this-week',
            'this-month',
            'this-year',
            'this-last-week',
            'this-last-fortnight',
            'this-last-month',
            'this-last-year',
            'last-week',
            'last-month',
            'last-bimester',
            'last-trimester',
            'last-quarter',
            'last-semester',
            'last-year'
        ];
    }

    /**
     * Return list of periods for widget select
     *
     * @param Translator $i18n
     *
     * @return array
     */
    public static function getWidgetOptions(&$i18n)
    {
        $result = [];
        foreach (self::getPeriods() as $value) {
            $result[] = ['value' => $value, 'title' => $i18n->trans($value)];
        }
        return $result;
    }
}