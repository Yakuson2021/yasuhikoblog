<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Profile;
use App\ProfileHistory;
use Carbon\Carbon;

//エラーになったので、追加（1/13）
use App\History;

class ProfileController extends Controller
{
    //カリキュラムで追記
        public function add()
    {
        return view('admin.profile.create');
    }

    public function create(Request $request)
    {
      // 以下を追記
      // Varidationを行う
      $this->validate($request, Profile::$rules);

      $profile = new Profile;
      $form = $request->all();
      
      // フォームから送信されてきた_tokenを削除する
      unset($form['_token']);
      // データベースに保存する
      $profile->fill($form);
      $profile->save();
      
      return redirect('admin/profile/create');
    }

    public function edit(Request $request)
      {
      // News Modelからデータを取得する
      $profile = Profile::find($request->id);
      if (empty($profile)) {
        abort(404);    
      }
         return view('admin/profile/edit', ['profile_form' => $profile]);
    }

    public function update()
    {
        $this->validate($request, Profile::$rules);
        $profile = Profile::find($request->id);
        $profile_form = $request->all();

        unset($profile_form['_token']);
      //unset($profile_form['remove']);　無効化に（1/13）
        $profile->fill($profile_form)->save();

        // 以下を追記
        $profilehistory = new ProfileHistory();
        $profilehistory->profile_id = $profile->id;
        $profilehistory->edited_at = Carbon::now();
        $profilehistory->save();
        return redirect('admin/profile/');
    }
}
