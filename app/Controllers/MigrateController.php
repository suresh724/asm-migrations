<?php

namespace App\Controllers;

ini_set('max_execution_time', 0);
ini_set('memory_limit', '2048M');


use App\Controllers\BaseController;
use App\Models\ShgProfile;
use App\Models\Villages;
use App\Models\LocalBodies;
use App\Models\Blocks;
use App\Models\Districts;
use App\Models\State;

class MigrateController extends BaseController
{
    public function index()
    {
        $shgModel = new ShgProfile();
        $blocksModel = new Blocks();
        $villageModel = new Villages();
        $localBodyModel = new LocalBodies();
        $villageModel = new Villages();
        $allShg = $shgModel->findAll(100, 0);
        foreach ($allShg as $key => $shg) {

            $villageName = $shg['village'];
            $localBodyName = $shg['local_body_name'];
            $blockName = $shg['block_name'];
            $shgId=$shg['id'];
            $blockCode = $blocksModel->where(["block_title" => $blockName, "", "state_code" => 27])->find();

            $blockCode = $blockCode[0]['block_code'];
            $localBodyCode = $localBodyModel->where(["block_code" => $blockCode, "local_body_name" => $localBodyName])->find();

            $localBodyCode = $localBodyCode[0]['local_body_code'];
            $villageData = $villageModel->where([
                "village_name" => $villageName, "local_body_code" => $localBodyCode
                ])->find();
            if (isset($villageData[0]['village_code'])) {
                $villageCode=$villageData[0]['village_code'];
                $updateData=[
                    "village_code"=>$villageCode,
                    "local_body"=>$localBodyCode,
                    "block"=>$blockCode
                ];
                // print_r($updateData);
                echo $shgId;
                echo '<br>';
                $shgModel->update($shgId,$updateData);
            };
            // echo '<br>';
            // $whereArr=[
            //     "state"
            // ]
            // $villageArr= $villageModel->where()
        }
        // $db      = \Config\Database::connect();
        // $builder = $db->table('shg_profile');
        // $query   = $builder->select("*")->from("blocks")->getRow();

        // print_r($query);

    }
}
