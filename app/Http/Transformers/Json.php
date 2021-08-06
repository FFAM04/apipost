<?php

namespace App\Http\Transformers;


use Illuminate\Pagination\LengthAwarePaginator;


/**
*  Class Json is transformers from raw data to json view
*/
class Json
{
    public static function response($data = null, $url = null, $page = null, $perPage = null, $message = null, $status = true, $additional=null)
    {
        if ($message==null) {
            $message = __('message.success');
        }
        if ($data==null) {
            $q['result'] = [];
            $q['pagination'] = null;
            $data = $q;
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
            // dd($url);
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
            $result['data']['result'] = $paginate->all();
            // dd($result);
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
            // dd($url);
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
    
    public static function response_paginate($object = null, $data = null, $pagination = null, $url_id = null, $jumlah = null, $message = null, $last = null, $status = true, $additional=null)
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
        if ($last!=null) {
            $last1 = $last;
        }else{
            $last1 = $pagination->lastPage();
        }
        // dd($last1);
        $result['status'] = $status;
        $result['message'] = $message;
        if ($url_id != null) {
            if($object != null){
                $result['data']['info'] = $object;
                // dd($result);
            }
            // dd($pagination);
            $result['data']['result'] = $data;
            $result['data']['pagination']['total'] = $jumlah;
            $result['data']['pagination']['offset'] = $pagination->perPage();
            $result['data']['pagination']['current'] =$pagination->currentpage();
            $result['data']['pagination']['last']=$last1;
            if($pagination->nextPageUrl() != null){
                $result['data']['pagination']['next']=$pagination->nextPageUrl().$url_id;
            }else{
                $result['data']['pagination']['next'] = null;
            }
            if($pagination->previousPageUrl() != null){
                $result['data']['pagination']['prev']=$pagination->previousPageUrl().$url_id;
            }else{
                $result['data']['pagination']['prev'] = null;
            }
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


