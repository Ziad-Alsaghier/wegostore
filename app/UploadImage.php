<?php

namespace App;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

trait UploadImage
{

  public function imageUpload($request, $inputName, $destinationPath)
  {
    if ($request->hasFile($inputName)) {
      // Get the image file
      $image = $request->file($inputName);
      // Move the image to the destination path
       $path = $request->file($inputName)->store($destinationPath, 'public');

      // Return the image URLk
     return $path;
    }

    return false; // Return false if no image was uploaded
  }
// This Funtion Take Request & Name Of Model
  protected function imageUpdate($request,$model,$imageName,$destinationPath){
        $OldImage = $model->$imageName; // Get Old Path Image
         if ($request->hasFile($imageName)) { // If Need Update Image
         $deletOldImage = $this->deleteImage($OldImage); // Delete Old Image
         $image = $this->imageUpload(request: $request,inputName:$imageName,destinationPath:$destinationPath); // create new Image
      return  $image;
         }
  }

  public function deleteImage($OldImage)
  {
    $imagePath =  public_path('storage/'.$OldImage);
    // Check if the file exists before deleting
    if (File::exists($imagePath)) {
      // Delete the file from the server
      File::delete($imagePath);
      return true; // File successfully deleted
    }

    return false; // File not found or deletion failed
  }


  
}
