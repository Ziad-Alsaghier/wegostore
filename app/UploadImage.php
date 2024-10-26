<?php

namespace App;
use Illuminate\Support\Facades\File;

trait UploadImage
{
    //
    public function imageUpload($request,$inputName,$destinationPath){
      if ($request->hasFile($inputName)) {
      // Get the image file
      $image = $request->file($inputName);

      // Generate a unique name for the image
      $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

      // Move the image to the destination path
      $image->move(storage_path($destinationPath), $imageName);

      // Return the image URL
      return $destinationPath . '/' . $imageName;
      }

      return false; // Return false if no image was uploaded
    }



      public function deleteImage($imagePath)
      {
      // Check if the file exists before deleting
      if (File::exists(public_path($imagePath))) {
      // Delete the file from the server
      File::delete(public_path($imagePath));
      return true; // File successfully deleted
      }

      return false; // File not found or deletion failed
      }
}
