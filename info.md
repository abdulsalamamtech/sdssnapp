
## Laravel Api Authentication Documentation
- [Docs](https://www.postman.com/sdnss2/laravel-api-authentication-documentation/overview)


## Api Docs
    Visit the docs[/docs/api] or [/docs/api.json]
- [Docs Api](https://scramble.dedoc.co/installation)
```sh

    composer require dedoc/scramble
    php artisan vendor:publish --provider="Dedoc\Scramble\ScrambleServiceProvider" --tag="scramble-config"

```


## Log Activities
- [papertrail](https://www.papertrail.com/)
- [slack](slack.com)


## Api File Modifications
    update config file cors.php and sanctum.php
    with the frontend domain name


## Laravel Cloudinary 
- [GitHub Docs](https://github.com/cloudinary-community/cloudinary-laravel/)
```sh

    composer require cloudinary-labs/cloudinary-laravel

    php artisan vendor:publish --provider="CloudinaryLabs\CloudinaryLaravel\CloudinaryServiceProvider" --tag="cloudinary-laravel-config"
    
    CLOUDINARY_URL=cloudinary://API_KEY:API_SECRET@CLOUD_NAME
    CLOUDINARY_UPLOAD_PRESET=your_upload_preset
    CLOUDINARY_NOTIFICATION_URL=
```
```php

        $validateRequest = $request->validate([
            'image' => ['required', 'image', 'max:2048'],
        ]);

        $cloudinaryImage = $request->file('image')->storeOnCloudinary('assets');
        $url = $cloudinaryImage->getSecurePath();
        $public_id = $cloudinaryImage->getPublicId();


        $data = [
            'url' => $url,
            'file_id' => $public_id,
            'cloudinary_image' => $cloudinaryImage,
            'more' => [
                $cloudinaryImage->getSize(), 
                $cloudinaryImage->getOriginalFileName(), 

                // Same ID
                // $cloudinaryImage->getFileName(),
                // $cloudinaryImage->getPublicId(),

                // Image or Video file
                $cloudinaryImage->getFileType(),
                // Dimensions
                $cloudinaryImage->getHeight(),
                $cloudinaryImage->getWidth(),
                $cloudinaryImage->getTags(),

                // Get file url
                $cloudinaryImage->getUrl( $public_id),


                // Cloudinary response
                $cloudinaryImage->getResponse(),
                // {
                //     "asset_id": "1d5d5409e10a2964892f49c3c38ec632",
                //     "public_id": "assets/yr8lgcmm84jrzx0wbcy0",
                //     "version": 1736440079,
                //     "version_id": "0592277f4944c3cc0591fedd0bdd9855",
                //     "signature": "cefd8bafd3c872b53577b1bc00f8daf6c85c615f",
                //     "width": 600,
                //     "height": 600,
                //     "format": "png",
                //     "resource_type": "image",
                //     "created_at": "2025-01-09T16:27:59Z",
                //     "tags": [],
                //     "bytes": 210790,
                //     "type": "upload",
                //     "etag": "072f339e3f371cec777f5bc363a29629",
                //     "placeholder": false,
                //     "url": "http://res.cloudinary.com/dpjdupkot/image/upload/v1736440079/assets/yr8lgcmm84jrzx0wbcy0.png",
                //     "secure_url": "https://res.cloudinary.com/dpjdupkot/image/upload/v1736440079/assets/yr8lgcmm84jrzx0wbcy0.png",
                //     "folder": "assets",
                //     "original_filename": "phpo27h3y",
                //     "api_key": "732422493399421"
                // }

                // Asset id on cloudinary
                $cloudinaryImage->getAssetId(),
            ]
        ];
``

```php

    // Upload
    $uploadedFileUrl = cloudinary()->upload($request->file('file')->getRealPath())->getSecurePath();

    // Upload with transformation
    $uploadedFileUrl = cloudinary()->upload($request->file('file')->getRealPath(), [
        'folder' => 'uploads',
        'transformation' => [
            'width' => 400,
            'height' => 400,
            'crop' => 'fill'
        ]
    ])->getSecurePath();

    // Get URL
    $url = cloudinary()->getUrl($publicId);

    // Check if file exists
    $exists = Storage::disk('cloudinary')->fileExists($publicId);
```