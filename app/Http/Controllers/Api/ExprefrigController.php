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
        
        public function getItem()
        {
            return BoardFactory::all();
        }

        public function getMember(Request $request){
            if (strcmp($request->auth,'$2y$12$ZOwD7oZr.jUt7f7YnVxdy.v4P8KDajFq17ueQT1Arw9QjHsS96x3q') == 0)
                return Member::where('uid',$request->uid)->select('picture','username','name')->get();
            else
                return '404 Not Found';
        }

        public function getByDevice($pv_key)
        {
            $item = app('db')->select("SELECT TIMESTAMPDIFF(SECOND,CONVERT_TZ(NOW(), @@session.time_zone, '+07:00'),dateTime) as sec FROM devices WHERE private_key = '$pv_key'");
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
            return Refrigerator::select('refrig_id','name_refrig')->where('uid',$uid)->get();
        }

        public function getMyDevice($uid)
        {
            return Device::select('private_key')->where('uid',$uid)->get();
        }

        public function getDevice($uid,$rid)
        {
            return app('db')->select('SELECT private_key,name,DATE_FORMAT(dateTime, "%d %M %Y") as dateExp,DATE_FORMAT(dateTime, "%T") as timeExp,DATE_FORMAT(dateTimeYellow, "%T") as timeYellowExp,image FROM devices WHERE uid='.$uid.' AND refrig_id = '.$rid);
        }

        public function register(Request $request)
        {
            $this->validate($request,[
                'name' => 'required',
                'uid' => 'required'
            ]);

            try
            {
                $refrigerator = new Refrigerator;
                $refrigerator->name_refrig = $request->name;
                $refrigerator->uid = $request->uid;
                $refrigerator->save();
                return 1;
            }catch (Exception $e){
                return $e;
            }
        }

        public function addRefrigerator(Request $request)
        {
            $this->validate($request,[
                'name' => 'required',
                'uid' => 'required'
            ]);

            try
            {
                $refrigerator = new Refrigerator;
                $refrigerator->name_refrig = $request->name;
                $refrigerator->uid = $request->uid;
                $refrigerator->save();
                return 1;
            }catch (Exception $e){
                return $e;
            }
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
                $item = app('db')->update("UPDATE devices SET name = '$request->name',refrig_id = '$request->id',dateTime = CONVERT_TZ('$request->datetime',@@session.time_zone, '+07:00'),dateTimeYellow = CONVERT_TZ('$request->datetimeYellow',@@session.time_zone, '+07:00') WHERE private_key = '$request->private_key'");
                return 1;
            }catch (Exception $e){
                return $e;
            }
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
                    $item = app('db')->update("UPDATE devices SET image='".$target_file."',updated_at='".\Carbon\Carbon::now()->toDateTimeString()."' WHERE private_key = '$pv_key'");
                    echo "The file ". basename( $_FILES["photo"]["name"]). " has been uploaded.";
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
            $this->validate($request,[
                'username' => 'required',
                'password' => 'required'
            ]);

            try
            {
                return Member::select('uid','name')
                    ->where('username',$request->username)
                    ->where('password',$request->password)
                    ->get();
            }catch (Exception $e){
                return $e;
            }
        }

        public function deleteItem(Request $request){
            $this->validate($request,[
                'private_key' => 'required'
            ]);
            try
            {
                return Device::where('private_key',$request->private_key)->update(['refrig_id'=>null]);
            }catch (Exception $e){
                return $e;
            }
        }

        public function putItem(Request $request,$id,$table)
        {
            $update = app('db')->update("UPDATE $table SET actived = '$request->status',own = '$request->uid' WHERE private_key = '$id'");
            $insert = app('db')->insert("INSERT INTO device(private_key,uid) VALUES ('$id','$request->uid')");
            return $update;
        }

        public function updateItem($table,$id,Request $request)
        {
            return app('db')->table($table)->where("refrig_id",$id)->update(["updated_at"=>\Carbon\Carbon::now()->toDateTimeString(),"name_refrig"=>$request->name_refrig]);
        }
        public function deleteRefrigerator($id)
        {
            try{
                Refrigerator::where('refrig_id',$id)->delete();
                return "success";
            } catch (\Exception $e){
                return "false";
            }
        }
    }
?>