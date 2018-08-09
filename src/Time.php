<?php

/**
 * Time.php
 * DQSEGDB - Convert segdb-format data to DQSEGDB.
 *
 * @link https://github.com/ligovirgo/dqsegdb/blob/master/server/db/db_utils/segdb_to_dqsegdb_auto_converter/src/classes/Time.php Source
 * @link https://www.andrews.edu/~tzs/timeconv/timealgorithm.html Algorithm
 */

namespace Bmatovu\Conversion;

/**
 * Class Time.
 */
class Time
{
    /**
     * Define GPS leap seconds.
     *
     * @return array
     */
    private static function getleaps()
    {
        $leaps = [
            46828800,
            78364801,
            109900802,
            173059203,
            252028804,
            315187205,
            346723206,
            393984007,
            425520008,
            457056009,
            504489610,
            551750411,
            599184012,
            820108813,
            914803214,
            1025136015,
        ];

        return $leaps;
    }

    /**
     * Test to see if a GPS second is a leap second.
     *
     * @param $gpsTime
     *
     * @return bool
     */
    private static function isleap($gpsTime)
    {
        $isLeap = false;
        $leaps = self::getleaps();
        $lenLeaps = count($leaps);
        for ($i = 0; $i < $lenLeaps; $i++) {
            if ($gpsTime == $leaps[$i]) {
                $isLeap = true;
            }
        }

        return $isLeap;
    }

    /**
     * Count number of leap seconds that have passed.
     *
     * @param $gpsTime
     * @param $dirFlag
     *
     * @return int
     */
    private static function countleaps($gpsTime, $dirFlag)
    {
        $leaps = self::getleaps();
        $lenLeaps = count($leaps);
        $nleaps = 0; // number of leap seconds prior to gpsTime
        for ($i = 0; $i < $lenLeaps; $i++) {
            if (!strcmp('unix2gps', $dirFlag)) {
                if ($gpsTime >= $leaps[$i] - $i) {
                    $nleaps++;
                }
            } elseif (!strcmp('gps2unix', $dirFlag)) {
                if ($gpsTime >= $leaps[$i]) {
                    $nleaps++;
                }
            } else {
                echo 'ERROR Invalid Flag!';
            }
        }

        return $nleaps;
    }

    /**
     * Convert GPS Time to Unix Time.
     *
     * @param $gpsTime
     *
     * @return mixed
     */
    public static function gps2unix($gpsTime)
    {
        // Add offset in seconds
        $unixTime = $gpsTime + 315964800;
        $nleaps = self::countleaps($gpsTime, 'gps2unix');
        $unixTime = $unixTime - $nleaps;
        if (self::isleap($gpsTime)) {
            $unixTime = $unixTime + 0.5;
        }

        return $unixTime;
    }

    /**
     * Convert Unix Time to GPS Time.
     *
     * @param $unixTime
     *
     * @return mixed
     */
    public static function unix2gps($unixTime)
    {
        // Add offset in seconds
        if (fmod($unixTime, 1) != 0) {
            $unixTime = $unixTime - 0.5;
            $isLeap = 1;
        } else {
            $isLeap = 0;
        }
        $gpsTime = $unixTime - 315964800;
        $nleaps = self::countleaps($gpsTime, 'unix2gps');
        $gpsTime = $gpsTime + $nleaps + $isLeap;

        return $gpsTime;
    }
}
