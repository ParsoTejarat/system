<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSoftwareUpdateRequest;
use App\Models\SoftwareUpdate;
use Hekmatinasser\Verta\Verta;
use Illuminate\Http\Request;

class SoftwareUpdateController extends Controller
{
    public function index()
    {
        $this->authorize('software-update-list');

        $software_updates = SoftwareUpdate::latest()->paginate(30);
        return view('panel.software-update.index', compact('software_updates'));
    }

    public function create()
    {
        $this->authorize('software-update-create');

        return view('panel.software-update.create');
    }

    public function store(StoreSoftwareUpdateRequest $request)
    {

        $this->authorize('software-update-create');

        $items = explode(',', $request->items);

        $software = SoftwareUpdate::create([
            'version' => $request->version,
            'date' => now(),
            'description' => json_encode($items),
        ]);


        activity_log('software-update-create', __METHOD__, [$request->all(), $software]);


        alert()->success('تغییرات نرم افزار با موفقیت افزوده شد', 'ثبت تغییرات');
        return redirect()->route('software-updates.index');
    }

    public function show(SoftwareUpdate $softwareUpdate)
    {
        //
    }

    public function edit(SoftwareUpdate $softwareUpdate)
    {
        $this->authorize('software-update-edit');

        return view('panel.software-update.edit', compact('softwareUpdate'));
    }

    public function update(Request $request, SoftwareUpdate $softwareUpdate)
    {
        $this->authorize('software-update-edit');

        $items = explode(',', $request->items);

        $softwareUpdate->update([
            'version' => $request->version,
            'date' => now(),
            'description' => json_encode($items),
        ]);

        activity_log('software-update-edit', __METHOD__, [$request->all(), $softwareUpdate]);

        alert()->success('تغییرات نرم افزار با موفقیت ویرایش شد', 'ویرایش تغییرات');
        return redirect()->route('software-updates.index');
    }

    public function destroy(SoftwareUpdate $softwareUpdate)
    {
        $this->authorize('software-update-delete');
        activity_log('software-update-delete', __METHOD__, [$softwareUpdate]);

        $softwareUpdate->delete();
        return back();
    }

//    public function versions()
//    {
//        $versions = SoftwareUpdate::latest()->paginate(30);
//        return view('panel.software-update.versions', compact('versions'));
//    }
}
