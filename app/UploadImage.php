<?php

namespace App;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

trait UploadImage
{
  //
  public function imageUpload($request, $inputName, $destinationPath)
  {
    if ($request->hasFile($inputName)) {
      // Get the image file
      $image = $request->file($inputName);

      // Move the image to the destination path
       $path = $request->file($inputName)->store($destinationPath, 'public');

      // Return the image URL
     return $path;
    }

    return false; // Return false if no image was uploaded
  }

 

  public function deleteImage($imagePath)
  {
     storage_path($imagePath);
    // Check if the file exists before deleting
    if (File::exists(storage_path($imagePath))) {
      // Delete the file from the server
      File::delete(storage_path($imagePath));
      return true; // File successfully deleted
    }

    return false; // File not found or deletion failed
  }


  
}
