<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('memory_limit', '1024M');
error_reporting(E_ALL);

    require '../config/newrelic.php'; // ## new relic set app name
    require '../config/connstr.php'; //## database conn string
    require '../config/memcache.php'; //## memcache stuff
    require '../api/logging.php'; // ### slim custom logging class

    $csvRow = [];

    $sql = "SELECT * FROM data_collection";

    $db = getConnection();
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $response = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $headings = null;

    $exclude = [
        'id'=>true,
        'player_type'=>true,
        'player_dob'=>true,
        'player_phone'=>true,
        'player_contact_email'=>true,
        'player_contact_sms'=>true,
        'player_guid'=>true,
        'player_datemodified' => true,
        'player_familyclub_optin'  => true



        player_already_familyclubmember
        player_familyclub_optin
        player_cityname


    ];

    foreach ($response as $row) {
        if($headings === null) {
            $headings = [];
            foreach($row as $col=>$data) {
                if(array_key_exists($col, $exclude)) {
                    continue;
                }
                $headings[] = $col;
            }
            $csvRow[] = $headings;
        }

        $newRow = [];
        foreach($row as $col=>$data) {
            if(array_key_exists($col, $exclude)) {
                continue;
            }
            $newRow[$col] = $data;
        }
        $csvRow[] = $newRow;
    }

    $fh = fopen('php://output', 'w');

    $filename = 'campaigndata-' . date('Ymd') . '_' . date('His');

    header("Pragma: public");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: private", false);
    header("Content-Type: application/octet-stream");
    header("Content-Disposition: attachment; filename=\"$filename.csv\";");
    header("Content-Transfer-Encoding: binary");

    if (!empty($csvRow)) {
        foreach ($csvRow as $item) {
            fputcsv($fh, $item);
        }
    }
