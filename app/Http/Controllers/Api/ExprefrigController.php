<?php

namespace App\Http\Controllers\Api;

use App\Model\Test;
use App\Model\Device;
use App\Model\Member;
use App\Model\Refrigerator;
use App\Model\BoardFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Routing\Controller as BaseController;

class ExprefrigController extends BaseController
{
    public function getNow()
    {
        date_default_timezone_set('Asia/Bangkok');
        return \Carbon\Carbon::now()->format('Y-m-d H:i:s');
    }

    public function getItem()
    {
        return BoardFactory::all();
    }

    public function getMember(Request $request)
    {
        if ($request->auth == "0fa2e78f70d377d5da274ebd4e8b5e1c")
            return Member::where('uid', $request->uid)->select('email', 'name')->get();
        else
            return abort(404);
    }

    public  function getAllMyDevice($uid){
        return Device::select("private_key")->where("uid",$uid)->get();
    }

    public function getByDevice($pv_key)
    {
        $item = app('db')->select("SELECT CONVERT_TZ(created_at, @@session.time_zone, '+07:00') as created_at,CONVERT_TZ(updated_at, @@session.time_zone, '+07:00') as updated_at,TIMESTAMPDIFF(SECOND,CONVERT_TZ(NOW(), @@session.time_zone, '+07:00'),dateTime) as secRed,TIMESTAMPDIFF(SECOND,CONVERT_TZ(NOW(), @@session.time_zone, '+07:00'),dateTimeYellow) as secYellow,name,image,dateTime FROM devices WHERE private_key = '$pv_key'");
        $hour = $item[0]->secRed / 3600;
        $min = ($item[0]->secRed - (floor($hour) * 3600)) / 60;
        $sec = ($item[0]->secRed - (floor($hour) * 3600)) - (floor($min) * 60);
        $hourY = $item[0]->secYellow / 3600;
        $minY = ($item[0]->secYellow - (floor($hourY) * 3600)) / 60;
        $secY = ($item[0]->secYellow - (floor($hourY) * 3600)) - (floor($minY) * 60);
        if ($hour < 0) {
            $hour = -1;
            $min = 0;
            $sec = 0;
        }
        if ($hourY < 0) {
            $hourY = -1;
            $minY = 0;
            $secY = 0;
        }
        $time = [
            "hour" => floor($hour),
            "min" => floor($min),
            "sec" => $sec,
            "hour2" => floor($hourY),
            "min2" => floor($minY),
            "sec2" => $secY,
            "name" => $item[0]->name,
            "image" => $item[0]->image,
            "dateTime" => $item[0]->dateTime,
            "created_at" => $item[0]->created_at,
            "updated_at" => $item[0]->updated_at
        ];
        return response()->json($time);
    }

    public function getByApplication($uid)
    {
        return Refrigerator::select('refrig_id', 'name_refrig')->where('uid', $uid)->get();
    }

    public function getMyDevice($uid)
    {
        return Device::select('private_key')->where('uid', $uid)->where('refrig_id', null)->get();
    }

    public function getDevice($uid, $rid)
    {
        date_default_timezone_set('Asia/Bangkok');
        $qr = Device::select('dateTime','dateTimeYellow','private_key','name','image')->where('uid',$uid)->where('refrig_id',$rid)->get();
        //$qr = app('db')->select('SELECT dateTime,dateTimeYellow,private_key,name,image FROM devices WHERE uid=' . $uid . ' AND refrig_id = ' . $rid);
        $now = \Carbon\Carbon::now()->format('Y-m-d H:i:s');
        $count = 0;
        foreach ($qr as $result){
            $status = "green";
            if (strtotime($now) > strtotime($result->dateTime))
                $status = "red";
            elseif (strtotime($now) > strtotime($result->dateTimeYellow))
                $status = "yellow";
            $result->status = $status;
            $result->id = $count;
            $count++;

        }
        return $qr;
    }

    public function register(Request $request)
    {
        $this->validate($request, [
            'auth' => 'required'
        ]);
        if ($request->auth == "0fa2e78f70d377d5da274ebd4e8b5e1c") {
            $member = new Member;
            $member->name = $request->name;
            $member->email = $request->email;
            $member->password = app('hash')->make($request->password);
            $member->save();
            return 1;
        } else
            return abort(404);
    }

    public function addRefrigerator(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'uid' => 'required'
        ]);

        try {
            $refrigerator = new Refrigerator;
            $refrigerator->name_refrig = $request->name;
            $refrigerator->uid = $request->uid;
            $refrigerator->save();
            return 1;
        } catch (Exception $e) {
            return $e;
        }
    }

    public function addItem(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'id' => 'required',
            'datetime' => 'required',
            'private_key' => 'required'
        ]);

        try {
            date_default_timezone_set("UTC");
            return Device::where('private_key', $request->private_key)
                ->update([
                    "name" => $request->name,
                    "refrig_id" => $request->id,
                    "dateTime" => date('Y-m-d H:i:s', strtotime($request->datetime)),
                    "dateTimeYellow" => date('Y-m-d H:i:s', strtotime($request->datetimeYellow))
                ]);
            //$item = app('db')->update("UPDATE devices SET name = '$request->name',refrig_id = '$request->id',dateTime = CONVERT_TZ('$request->datetime',@@session.time_zone, '+07:00'),dateTimeYellow = CONVERT_TZ('$request->datetimeYellow',@@session.time_zone, '+07:00') WHERE private_key = '$request->private_key'");
        } catch (Exception $e) {
            return $e;
        }
    }

    public function addImage($pv_key)
    {
        $target_dir = "storage/";
        $target_file = $target_dir . basename($_FILES["photo"]["name"]);
        $uploadOk = 1;
        $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
        $check = getimagesize($_FILES["photo"]["tmp_name"]);
        if ($check !== false) {
            echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
            if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
                $item = app('db')->update("UPDATE devices SET image='" . $target_file . "',updated_at='" . \Carbon\Carbon::now()->toDateTimeString() . "' WHERE private_key = '$pv_key'");
                echo "The file " . basename($_FILES["photo"]["name"]) . " has been uploaded.";
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required',
            'password' => 'required'
        ]);

        try {
            $member_pwd = Member::select('password')->where('email', $request->email)->get();
            if (app('hash')->check($request->password,$member_pwd[0]->password)) {
                return Member::select('uid','name','email')
                    ->where('email', $request->email)
                    ->get();
            }
            else{
                return abort(404);
            }
        } catch (Exception $e) {
            return $e;
        }
    }

    public function deleteItem(Request $request)
    {
        $this->validate($request, [
            'private_key' => 'required'
        ]);
        try {
            return Device::where('private_key', $request->private_key)->update(['refrig_id' => null]);
        } catch (Exception $e) {
            return $e;
        }
    }


    public function activated(Request $request)
    {
        Device::where('private_key', $request->pv_key)->update(['uid' => $request->uid]);
        return BoardFactory::where('private_key', $request->pv_key)->update(['activated' => $request->status, 'own' => $request->uid]);
    }

    public function putItem(Request $request, $id, $table)
    {
        $update = app('db')->update("UPDATE $table SET activated = '$request->status',own = '$request->uid' WHERE private_key = '$id'");
        BoardFactory::update(['activated' => 'yes', 'own' => $request->uid])->where('private_key', $id);
        return $update;
    }

    public function updateItem($table, $id, Request $request)
    {
        return app('db')->table($table)->where("refrig_id", $id)->update(["updated_at" => \Carbon\Carbon::now()->toDateTimeString(), "name_refrig" => $request->name_refrig]);
    }

    public function updateProfile(Request $request,$uid){
        $this->validate($request, [
            'auth' => 'required'
        ]);
        if ($request->auth == "0fa2e78f70d377d5da274ebd4e8b5e1c") {
            if (isset($request->password)){
                $newPass = app('hash')->make($request->password);
                return Member::where("uid",$uid)->update(["name"=>$request->name,"password"=>$newPass,"updated_at" => \Carbon\Carbon::now()->toDateTimeString()]);
            }
            else{
                return Member::where("uid",$uid)->update(["name"=>$request->name,"updated_at" => \Carbon\Carbon::now()->toDateTimeString()]);
            }
        } else
            return abort(405);
    }

    public function deleteRefrigerator($id)
    {
        try {
            Refrigerator::where('refrig_id', $id)->delete();
            return "success";
        } catch (\Exception $e) {
            return "false";
        }
    }

    public function deleteDevice($id)
    {
        try {
            Device::where('private_key', $id)->update(["uid" => null,"refrig_id" => null]);
            BoardFactory::where('private_key', $id)->update(["activated"=>"no","own"=>null]);
            return "success";
        } catch (\Exception $e) {
            return "false";
        }
    }
}

?>