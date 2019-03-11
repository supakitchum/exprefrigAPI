<?php
    namespace App\Http\Controllers\Api;

    use App\Model\Test;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\DB;
    use Laravel\Lumen\Routing\Controller as BaseController;

    class TestController extends BaseController
    {
        
        public function getItem()
        {
            $item = app('db')->select("SELECT * FROM board_factory");
            return $item;
        }

        public function getByDevice($pv_key)
        {
            $item = app('db')->select("SELECT TIMESTAMPDIFF(SECOND,CONVERT_TZ(NOW(), @@session.time_zone, '+07:00'),dateTime) as sec FROM device WHERE private_key = '$pv_key'");
            $hour = $item[0]->sec / 3600;
            $min = ($item[0]->sec - (floor($hour)*3600))/60;
            $sec = ($item[0]->sec - (floor($hour)*3600)) - (floor($min) * 60);
            if ($hour < 0){
                $hour = -1;
                $min = 0;
                $sec =0;
            }
            $time = [
                "hour"=>floor($hour),
                "min"=>floor($min),
                "sec"=>$sec,
            ];
            return response()->json($time);
        }

        public function getByApplication($uid)
        {
            $item = app('db')->select("SELECT refrig_id,name_refrig FROM refrigerator WHERE uid='$uid'");
            return $item;
        }

        public function getMyDevice($uid)
        {
            $item = app('db')->select("SELECT private_key FROM device WHERE uid='$uid'");
            return $item;
        }

        public function getDevice($uid,$rid)
        {
            $item = app('db')->select('SELECT private_key,name,DATE_FORMAT(dateTime, "%d %M %Y") as dateExp,DATE_FORMAT(dateTime, "%T") as timeExp,DATE_FORMAT(dateTimeYellow, "%T") as timeYellowExp,image FROM device WHERE uid='.$uid.' AND refrig_id = '.$rid);
            return $item;
        }

        public function register(Request $request)
        {
            $this->validate($request,[
                'name' => 'required',
                'uid' => 'required'
            ]);

            try
            {
                $item = app('db')->insert("INSERT INTO refrigerator(name_refrig,uid) VALUES ('$request->name','$request->uid')");
            }catch (Exception $e){
                return 0;
            }
            return 1;
        }

        public function addRefrigerator(Request $request)
        {
            $this->validate($request,[
                'name' => 'required',
                'uid' => 'required'
            ]);

            try
            {
                $item = app('db')->insert("INSERT INTO refrigerator(name_refrig,uid) VALUES ('$request->name','$request->uid')");
            }catch (Exception $e){
                return 0;
            }
            return 1;
        }

        public function addItem(Request $request)
        {
            $this->validate($request,[
                'name' => 'required',
                'id' => 'required',
                'datetime' => 'required',
                'private_key' => 'required'
            ]);

            try
            {
                $item = app('db')->update("UPDATE device SET name = '$request->name',refrig_id = '$request->id',dateTime = CONVERT_TZ('$request->datetime',@@session.time_zone, '+07:00'),dateTimeYellow = CONVERT_TZ('$request->datetimeYellow',@@session.time_zone, '+07:00') WHERE private_key = '$request->private_key'");
            }catch (Exception $e){
                return 0;
            }
            return 1;
        }

        public function addImage($pv_key)
        {
            $target_dir = "storage/";
            $target_file = $target_dir . basename($_FILES["photo"]["name"]);
            $uploadOk = 1;
            $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
            $check = getimagesize($_FILES["photo"]["tmp_name"]);
            if($check !== false) {
                echo "File is an image - " . $check["mime"] . ".";
                $uploadOk = 1;
                if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
                    $item = app('db')->update("UPDATE device SET image='".$target_file."' WHERE private_key = '$pv_key'");
                    echo "The file ". basename( $_FILES["photo"]["name"]). " has been uploaded.";
                } else {
                    echo "Sorry, there was an error uploading your file.";
                }
            } else {
                echo "File is not an image.";
                $uploadOk = 0;
            }
//            try{
//                $image = $request->file('photo');
//                $input['imagename'] = time().'.'.$image->getClientOriginalExtension();
//                $destinationPath = public_path('/storage');
//                $image->move($destinationPath, $input['imagename']);
//                $item = app('db')->update("UPDATE device SET image='".$input['imagename']."') WHERE private_key = '$request->private_key'");
//            }catch (Exception $e){
//                return "The image has upload failed.";
//            }
//            return "The image has been uploaded";
        }

        public function login(Request $request)
        {
            $this->validate($request,[
                'username' => 'required',
                'password' => 'required'
            ]);

            try
            {
                $item = app('db')->select("SELECT uid,name FROM member WHERE username='$request->username' AND password='$request->password'");
            }catch (Exception $e){
                return 0;
            }
            return $item;
        }

        public function deleteItem(Request $request){
            $this->validate($request,[
                'private_key' => 'required'
            ]);
            try
            {
                app('db')->table('device')
                    ->where('private_key',$request->private_key)
                    ->update(['refrig_id'=>null]);
            }catch (Exception $e){
                return $e;
            }
            return 1;
        }

        public function putItem(Request $request,$id,$table)
        {
            $update = app('db')->update("UPDATE $table SET actived = '$request->status',own = '$request->uid' WHERE private_key = '$id'");
            $insert = app('db')->insert("INSERT INTO device(private_key,uid) VALUES ('$id','$request->uid')");
            return $update;
        }

        public function updateItem($table,$id,Request $request)
        {
            $update = app('db')
                ->table($table)
                ->where("refrig_id",$id)
                ->update($request->all());
            return $update;
        }
        public function deleteRefrigerator($id)
        {
            try{
                $item = app('db')->delete("DELETE FROM refrigerator WHERE refrig_id=$id");
                return "success";
            } catch (\Exception $e){
                return "false";
            }
        }
    }
?>