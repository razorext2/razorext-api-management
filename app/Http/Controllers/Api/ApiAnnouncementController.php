<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResource;
use App\Jobs\BroadcastNewAnnouncementJob;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ApiAnnouncementController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'description' => 'required|string',
        ]);

        if ($validator->fails()) {
            return new ApiResource(false, 'Validasi gagal', $validator->errors()->first());
        }

        try {
            DB::beginTransaction();
            $announcement = Announcement::create($request->all());

            if (! $announcement) {
                return new ApiResource(false, 'Pengumuman gagal ditambahkan', null);
            }

            BroadcastNewAnnouncementJob::dispatch($announcement);

            Announcement::where('id', '!=', $announcement->id)
                ->update([
                    'status' => 0,
                ]);

            $count = Announcement::count();

            if ($count > 20) {
                Announcement::orderBy('created_at', 'asc')
                    ->limit(10)
                    ->delete();
            }

            DB::commit();

            return new ApiResource(true, 'Pengumuman berhasil ditambahkan', $announcement);
        } catch (\Exception $e) {
            DB::rollBack();

            return new ApiResource(false, 'Terjadi kesalahan saat menambahkan pengumuman', $e->getMessage());
        }
    }

    public function show($id)
    {

        $announcement = Announcement::find($id);

        if (! $announcement) {
            return new ApiResource(false, 'Data pengumuman tidak ditemukan', null);
        }

        if ($announcement->status == 1) {
            return new ApiResource(false, 'Tidak dapat merubah pengumuman.', 'Pengumuman sedang berlangsung, silahkan nonaktifkan terlebih dahulu.');
        }

        return new ApiResource(true, 'Data pengumuman ditemukan', $announcement);
    }

    public function changeState(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'state' => 'integer|max_digits:1',
        ]);

        if ($validator->fails()) {
            return new ApiResource(false, 'Gagal mengubah status pengumuman', $validator->errors()->first());
        }

        $announcement = Announcement::where('status', 1);

        try {
            DB::beginTransaction();

            if ($announcement->count() > 0) {
                $announcement->where('id', '!=', $id)->update(['status' => 0]);
            }

            $query = Announcement::findOrFail($id);

            if ($query->status == $request->state) {
                return new ApiResource(false, 'Status pengumuman saat ini sama dengan status yang diubah', null);
            }

            $query->update([
                'status' => $request->state,
            ]);

            if ($query->status == 1) {
                BroadcastNewAnnouncementJob::dispatch($query)
                    ->delay(now()
                        ->addSeconds(5));
            }

            DB::commit();

            return new ApiResource(true, 'Status pengumuman berhasil diubah', null);
        } catch (\Exception $e) {
            DB::rollBack();

            return new ApiResource(false, 'Terjadi kesalahan saat mengubah status pengumuman', $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'description' => 'required|string',
        ]);

        if ($validator->fails()) {
            return new ApiResource(false, 'Validasi gagal', $validator->errors()->first());
        }

        $announcement = Announcement::find($id);

        if (! $announcement) {
            return new ApiResource(false, 'Data pengumuman tidak ditemukan', null);
        }

        try {
            $data = $validator->validated();

            $announcement->update($data);

            return new ApiResource(true, 'Data pengumuman berhasil diperbarui', null);
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return new ApiResource(false, 'Terjadi kesalahan saat mengubah pengumuman', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $query = Announcement::findOrFail($id);

        if (! $query) {
            return new ApiResource(false, 'Data tidak ditemukan', null);
        }

        try {
            $query->delete();

            return new ApiResource(true, 'Data berhasil dihapus', null);
        } catch (\Exception $e) {
            return new ApiResource(false, 'Terjadi kesalahan saat menghapus data', $e->getMessage());
        }
    }
}
