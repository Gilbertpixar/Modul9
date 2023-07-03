<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Position;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
// use App\Http\Controllers\DB;



class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'Employee List';

        // ELOQUENT
        $employees = Employee::all();

        return view('employee.index', [
            'pageTitle' => $pageTitle,
            'employees' => $employees
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pageTitle = 'Create Employee';

        // ELOQUENT
        $positions = Position::all();

        return view('employee.create', compact('pageTitle', 'positions'));
    }

    public function store(Request $request)
    {
        $messages = [
            'required' => ':Attribute harus diisi.',
            'email' => 'Isi :attribute dengan format yang benar',
            'numeric' => 'Isi :attribute dengan angka'
        ];

        $validator = Validator::make($request->all(), [
            'firstName' => 'required',
            'lastName' => 'required',
            'email' => 'required|email',
            'age' => 'required|numeric',
        ], $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Get File
        $file = $request->file('cv');

        if ($file != null) {
            $originalFilename = $file->getClientOriginalName();
            $encryptedFilename = $file->hashName();

            // Store File
            $file->store('public/files');
        }
        // ELOQUENT
        $employee = New Employee;
        $employee->firstname = $request->firstName;
        $employee->lastname = $request->lastName;
        $employee->email = $request->email;
        $employee->age = $request->age;
        $employee->position_id = $request->position;

        if ($file != null) {
            $employee->original_filename = $originalFilename;
            $employee->encrypted_filename = $encryptedFilename;
        }

        $employee->save();

        return redirect()->route('employees.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pageTitle = 'Employee Detail';

        // ELOQUENT
        $employee = Employee::find($id);

        return view('employee.show', compact('pageTitle', 'employee'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pageTitle = 'Edit Employee';



        // ELOQUENT
        $positions = Position::all();
        $employee = Employee::find($id);

        return view('employee.edit', compact('pageTitle', 'positions', 'employee'));
    }

    public function update(Request $request, string $id)
    {
        $messages = [
            'required' => ':Attribute harus diisi.',
            'email' => 'Isi :attribute dengan format yang benar',
            'numeric' => 'Isi :attribute dengan angka'
        ];

        $validator = Validator::make($request->all(), [
            'firstName' => 'required',
            'lastName' => 'required',
            'email' => 'required|email',
            'age' => 'required|numeric',
        ], $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $employee = Employee::find($id);
        // Get File
        $file = $request->file('cv');

        if ($file != null) {
            $originalFilename = $file->getClientOriginalName();
            $encryptedFilename = $file->hashName();

            Storage::disk('public')->delete('files/'.$employee->encrypted_filename);

            // Store File
            $file->store('public/files');
        }

        // ELOQUENT

        $employee->firstname = $request->firstName;
        $employee->lastname = $request->lastName;
        $employee->email = $request->email;
        $employee->age = $request->age;
        $employee->position_id = $request->position;

        if ($file != null) {
            $employee->original_filename = $originalFilename;
            $employee->encrypted_filename = $encryptedFilename;
        }

        $employee->save();

        return redirect()->route('employees.index');
    }


    public function downloadFile($employeeId)
    {
        $employee = Employee::find($employeeId);
        $encryptedFilename = 'public/files/'.$employee->encrypted_filename;
        $downloadFilename = Str::lower($employee->firstname.'_'.$employee->lastname.'_cv.pdf');

        if(Storage::exists($encryptedFilename)) {
            return Storage::download($encryptedFilename, $downloadFilename);
        }
    }

    // public function updateCV(Request $request)
    // {
    //     // Validasi input
    //     $request->validate([
    //         'cv' => 'required|file',
    //     ]);

    //     // Mendapatkan file CV yang lama
    //     $oldpath = storage_path('app/public/files'); // Ganti dengan path file CV yang lama sesuai dengan kebutuhan Anda

    //     // Menghapus file CV yang lama
    //     if (file_exists($oldpath)) {
    //         unlink($oldpath);
    //     }

    //     // Mengunggah file CV yang baru
    //     $newPath = $request->file('cv')->storeAs('public/files', '_cv.pdf');

    //     // Redirect atau memberikan respons sukses kepada pengguna
    //     return redirect()->back()->with('success', 'CV berhasil diperbarui.');
    // }
    // public function updateCV(Request $request)
    // {
    //     // Validasi input
    //     $request->validate([
    //         'cv' => 'required|file',
    //     ]);

    //     // Mendapatkan file CV yang lama (jika ada)
    //     $oldCVPath = 'cv/_cv.pdf'; // Ganti dengan path file CV yang lama sesuai dengan kebutuhan Anda

    //     // Menghapus file CV yang lama (jika ada)
    //     if (Storage::exists($oldCVPath)) {
    //         Storage::delete($oldCVPath);
    //     }

    //     // Mengunggah file CV yang baru
    //     $newCVPath = $request->file('cv')->store('cv');

    //     // Redirect atau memberikan respons sukses kepada pengguna
    //     return redirect()->back()->with('success', 'CV berhasil diperbarui.');
    // }

    // public function updateCV(Request $request)
    // {
    //     // Validasi input
    //     $request->validate([
    //         'cv' => 'required|file',
    //     ]);

    //     // Menghapus file lama (jika ada)
    //     $oldFilePath = 'path/to/old/file'; // Ganti dengan path file yang lama

    //     if (Storage::disk('public')->exists($oldFilePath)) {
    //         Storage::disk('public')->delete($oldFilePath);
    //     }

    //     // Mengunggah file baru
    //     $newFile = $request->file('file');
    //     $newFilePath = $newFile->store('path/to/store', 'public');

    //     // Lanjutkan dengan pembaruan data atau operasi lain yang diperlukan

    //     // Redirect atau berikan respons sukses kepada pengguna
    //     return redirect()->back()->with('success', 'File berhasil diperbarui.');
    // }

    /**
     * Remove the specified resource from storage.
     */

    public function destroy(string $id)
    {
        // ELOQUENT
        // Employee::find($id)->delete();
        $employee = Employee::find($id);
        $encryptedFilename = $employee->encrypted_filename;

        $employee->delete();

        Storage::disk('public')->delete('files/'.$encryptedFilename);

        return redirect()->route('employees.index');
    }


}
