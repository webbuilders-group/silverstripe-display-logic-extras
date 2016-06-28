Included Features/Additions
=================
### UploadField
Display Logic Extras adds support for controlling field visibility based on whether an UploadField has an uploaded/selected file, has uploaded/selected at least or less than a given number of files.


```php
public function getCMSFields() {
    $fields = parent::getCMSFields();

    $fields->addFieldsToTab('Root.Text', array(
        UploadField::create('BackgroundImage', 'Background Image'),
        DisplayLogicWrapper::create(
            new OptionsetField('BackgroundRepeat', 'Background Image Repeat Style', array(
                                'back-no-repeat'=>'None',
                                'back-repeat-x'=>'Horizontally',
                                'back-repeat-y'=>'Vertically',
                                'back-repeat'=>'Both'
                            ), 'back-no-repeat')
        )->displayIf('BackgroundImage')->hasUpload()->end(),

        UploadField::create('Documents', 'Important Documents'),
        CheckboxField::create('TileFileList', 'Tile Documents List?')->displayIf('Documents')->hasUploadedAtLeast(6)->end(),
        CheckboxField::create('BoldList', 'Bold Documents List?')->displayIf('Documents')->hasUploadedLessThan(6)->end()
    ));

    return $fields;
}
```
