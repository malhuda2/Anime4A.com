<?php
/**
 * Created by PhpStorm.
 * User: Azure Cloud
 * Date: 11/9/2016
 * Time: 4:52 PM
 */

namespace App\Http\Controllers;

use App;
use App\Providers\AppServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;

use App\Library\MyFunction;
use App\DBAnimes;

class ACPAnimePagesController extends Controller
{
    public function AnimeList(Request $request)
    {
        $items = DBAnimes::all();

        return View::make('admincp.ACPAnimeListView', array('items' => $items))->render();
    }

    public function AnimeEditor(Request $request)
    {
        $typeList = App\DBType::all();
        $categoryList = App\DBCategory::all();
        $countryList = App\DBCountry::all();
        $directorList = App\DBDirector::all();
        $charList = App\DBChar::all();
        $producerList = App\DBProducer::all();
        $statusList = App\DBStatus::all();

        if(Input::has('id')){
            $id = Input::get('id');
            $anime = DBAnimes::find($id);
            Session::put('edit_id', $id);

            return View::make('admincp.ACPAnimeEditor',[
                'anime' => $anime,
                'typeList' => $typeList,
                'categoryList' => $categoryList,
                'countryList' => $countryList,
                'directorList' => $directorList,
                'charList' => $charList,
                'producerList' => $producerList,
                'statusList' => $statusList
            ])->render();
        }
        else{
            Session::forget('edit_id');
            return View::make('admincp.ACPAnimeEditor',[
                'typeList' => $typeList,
                'categoryList' => $categoryList,
                'countryList' => $countryList,
                'directorList' => $directorList,
                'charList' => $charList,
                'producerList' => $producerList,
                'statusList' => $statusList
            ])->render();
        }
    }

    public function AnimeNew(Request $request)
    {
        Session::forget('edit_id');
        return View::make('admincp.ACPAnimeEditor', array('' => ''))->render();
    }

    public function AnimeSave(Request $request)
    {
        try {
            if (Session::has('edit_id')) {
                $id = Session::get('edit_id');
                $anime = DBAnimes::find($id);
            } else {
                $anime = new DBAnimes();
            }
            $anime->name = Input::get('name');
            $anime->name_en = Input::get('name_en');
            $anime->name_jp = Input::get('name_jp');
            $date = Input::get('release_date');
            if (strlen($date))
                $anime->release_date = $date;
            $anime->type = Input::get('type');

            // join category id
            $cat = Input::get('category');
            if (count($cat))
                $anime->category = join(',', $cat);

            $anime->country = Input::get('country');
            $anime->director = Input::get('director');
            $anime->char = Input::get('char');
            $anime->producer = Input::get('producer');

            $ep_new = Input::get('episode_new');
            if (strlen($ep_new))
                $anime->episode_new = $ep_new;

            $ep_total = Input::get('episode_total');
            if (strlen($ep_new))
                $anime->episode_total = $ep_total;

            // upload image
            $imgFileName = '';
            // check input valid
            if (Input::hasFile('img')) {
                $imgFile = Input::file('img'); // get file
                // is file
                if ($imgFile->isValid()) {
                    // have old file
                    if(strlen($anime->img))
                    {
                        // delete old file
                        $oldFilePath = env('APP_HOME') . substr($anime->img,strrpos($anime->img, '/img/'));
                        if(is_file($oldFilePath))
                            unlink($oldFilePath);
                    }
                    $destinationPath = 'img'; // upload path
                    $name = $imgFile->getClientOriginalName(); // getting image name
                    $filename = pathinfo($name, PATHINFO_FILENAME);
                    $extension = $imgFile->getClientOriginalExtension(); // getting image extension
                    $imgFileName = $filename . '-' . rand(11111,99999).'.'.$extension; // renameing image
                    $imgFile->move($destinationPath, $imgFileName); // uploading file to given path
                }
            }
            if(strlen($imgFileName))
                $anime->img = Request::root() . '/img/' . $imgFileName;

            $bannerFileName = '';
            if (Input::hasFile('banner'))
            {
                $bannerFile = Input::file('banner');
                if ($bannerFile->isValid()) {
                    if(strlen($anime->banner))
                    {
                        // delete old file
                        $oldFilePath = env('APP_HOME') . substr($anime->banner,strrpos($anime->banner, '/banner/'));
                        if(is_file($oldFilePath))
                            unlink($oldFilePath);
                    }
                    $destinationPath = 'banner';
                    $name = $bannerFile->getClientOriginalName();
                    $filename = pathinfo($name, PATHINFO_FILENAME);
                    $extension = $bannerFile->getClientOriginalExtension();
                    $bannerFileName = $filename . '-' . rand(11111,99999).'.'.$extension;
                    $bannerFile->move($destinationPath, $bannerFileName);
                }
            }
            if(strlen($bannerFileName))
                $anime->banner = Request::root() . '/banner/' . $bannerFileName;

            $anime->status = Input::get('status');
            $anime->trailer = Input::get('trailer');
            $anime->tag = Input::get('tag');
            $anime->url = Input::get('url');
			$hot = Input::get('hot');
			if(strlen($hot))
				$anime->hot = $hot;

            if(filter_var(Input::get('enabled'), FILTER_VALIDATE_BOOLEAN))
                $anime->enabled = 1;
            else
                $anime->enabled = 0;
            $anime->description = Input::get('description');

            $anime->save();

            // remove edit id session
            Session::forget('edit_id');

            return '<div class="report">Lưu thành công!</div>';
        } catch (\Exception $e) {
            $error_code = $e->errorInfo[1];
            if($error_code == 1062){
                return '<div class="report">Lỗi trùng dữ liệu!</div>';
            }
            return $e->getMessage();
        } finally {
        }
    }

    public function AnimeDelete(Request $request)
    {
        try
        {
            // Delete code
            if(Input::has('id'))
            {
                $ids = Input::get('id');
                foreach ($ids as $i){
                    DBAnimes::destroy($i);
                }

                $items = DBAnimes::all();
                return View::make('admincp.ACPAnimeListView', array('items' => $items))->render();
            }
        }
        catch(\Exception $e)
        {
            return $e->getMessage();
        }
    }
}