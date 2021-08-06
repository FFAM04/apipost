<?php

namespace App\Http\Transformers;

use Storage;



/**
*  Class Json is transformers from raw data to json view
*/
class Upload
{
    public static function user_picture($file = null, $id_user = null, $message = null, $status = true)
    {
        if ($message==null) {
            $message = __('message.success');
        }
        if ($id_user==null){
            return response()->json(['error' => 'User Tidak Ditemukan'], 400);
        }
        $result['status'] = $status;
        $result['message'] = $message;
        if ($file==null) {
            $file = [];
        } else{
            $time = strtotime(date(now())) * 1000;
            $ekstensi = $file->getClientOriginalExtension();
            // dd($ekstensi);
            if($ekstensi != 'png' && $ekstensi != 'jpg' && $ekstensi != 'jpeg' && $ekstensi != 'gif'){
                // dd($ekstensi);
                $code = 200;
                $result['status'] = false;
                $result['message'] = 'Silahkan Upload Kembali Foto';
                $result['data'] = array();

                return response()->json($result,$code);
            }

            $new_imgname = $time.'.'.$ekstensi;
            $filePath = 'user-profile-picture/' . $new_imgname;
            $uploaded = Storage::disk('gcs')->put($filePath, file_get_contents($file));
            // dd($uploaded);
            // $ImageUpload = Image::make($file);
            // $ImageUpload->resize(250,125);
            // $thumbnailPath = 'storage/public/foto-thumbnail/';
            // $ImageUpload = $ImageUpload->save($new_imgname);
        }
        if($file != null){
            $img_usr = ['url_foto' => $filePath];
            Users::where('id_user', $id_user)->update($img_usr);
        }
        $code = 200;
        return response()->json($result, $code);
    }

    public static function exception($message = null, $code=200, $error = null, $status = false)
    {
        if ($message==null) {
            $message = __('message.error');
        }
        $result['data'] = [];
        $result['meta']['status'] = $status;
        $result['meta']['message'] = $message;
        $result['meta']['code'] = $code;

        if ($error instanceof \Exception) {
            $result['error']['message'] = $error->getMessage();
            $result['error']['file'] = $error->getFile();
            $result['error']['line'] = $error->getLine();
        } else {
           $result['error'] = $error;
        }
        return response()->json($result, $code);
    }

    public static function double_response($object = null, $data = null, $url = null, $page = null, $perPage = null, $message = null, $status = true, $additional=null)
    {
        if ($message==null) {
            $message = __('message.success');
        }
        if ($data==null) {
            $data = [];
        }
        if ($object==null) {
            $object = [];
        }
        $result['status'] = $status;
        $result['message'] = $message;
        if ($url != null) {
            $collection = collect($data);
            // dd($collection);
            // $perPage = 10;
            $paginate = new LengthAwarePaginator(
                $collection->forPage($page, $perPage),
                $collection->count(),
                $perPage,
                $page,
                $url
            );
            // dd($paginate);
            // $ret = [];
            // foreach($paginate->all() as $val){
            //     // dd($val['id_sesi']);
            //     $arr = [
            //         'id_sesi' => $val['id_sesi'],
            //         'kode' => $val['kode'],
            //         'judul' => $val['judul'],
            //         'deskripsi' => $val['deskripsi'],
            //         'waktu_dibuat' => $val['waktu_dibuat'],
            //         'waktu_mulai' => $val['waktu_mulai'],
            //         'waktu_selesai' => $val['waktu_selesai'],
            //         'total_peserta' => $val['total_peserta'],
            //     ];
            //     array_push($ret, $arr);
            // }
            // $result['data'] = $ret;
            // dd($object);
            if($object != null){
                $result['data']['info'] = $object;
                // dd($result);
            }
            $result['data']['result'] = $paginate->all();
            $result['data']['pagination']['total'] = $paginate->total();
            $result['data']['pagination']['offset'] = $paginate->perPage();
            $result['data']['pagination']['current'] =$paginate->currentpage();
            $result['data']['pagination']['last']=$paginate->lastPage();
            $result['data']['pagination']['next']=$paginate->nextPageUrl();
            $result['data']['pagination']['prev']=$paginate->previousPageUrl();
            // dd($result);
        } else {
            $result['data'] = $data;
            // dd($result);
        }
        if ($additional!=null) {
            foreach ($additional as $add) {
                $result[$add['name']] = $add['data'];
            }
        }
        // $result['code'] = 200;
        $code = 200;
        return response()->json($result, $code);
    }

}


