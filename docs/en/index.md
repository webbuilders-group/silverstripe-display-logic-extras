Included Features/Additions
=================
## Additional/Expanded Logic Support
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


## Workarounds/Hacks

### Display Logic not working in fields such as WidgetAreaEditor
Fields such as the WidgetAreaEditor provided by the [silverstripe/widgets](https://github.com/silverstripe/silverstripe-widgets) module rename fields to account for nesting the data from the fields into themselves. This module provides support for changing the name of the masters to account for this. You can call the ``prefixMasters`` method on the display logic criteria to wrap the name of the master. For example, looking at widgets:

```php
public function CMSEditor() {
    $fields=parent::CMSEditor();

    foreach($fields as $field) {
        $field->getDisplayLogicCriteria()->prefixMasters('Widget['.$this->ID.']');
    }

    return $fields;
}
```

The above example would be added to your custom ``Widget`` extension and would apply the necessary prefix to each top level field. For CompositeField extensions you probably want to get into some kind of recursion to ensure it's decedents have the same applied. For example:

```php
protected function prefixNestedMasters($fields, $depth=0) {
    //Recursion protection
    if($depth>10) {
        user_error('Too much recursion', E_USER_ERROR);
    }


    //Verify we're looking at a FieldList or CompositeField
    if(!($fields instanceof FieldList) && !($fields instanceof CompositeField)) {
        user_error('Argument 1 passed to prefixNestedMasters() must be an instance of FieldList or CompositeField', E_USER_ERROR);
    }


    //Loop through each field and rename
    foreach($fields as $field) {
        //Fix display_logic
        if($field->getDisplayLogicCriteria()) {
            $field->getDisplayLogicCriteria()->prefixMasters('Widget['.$this->ID.']');
        }

        //If we're looking at a FieldList or Composite Field go down into it
        if($field instanceof CompositeField) {
            $depth++;
            $this->prefixNestedMasters($field->FieldList(), $depth);
        }
    }
}
```


### FieldGroup Nested Field as Masters
Due to an inconsistency in how FieldGroup's add fields to the rendered DOM they are missing the necessary holder id, this module includes the change detailed in [unclecheese/silverstripe-display-logic#58](https://github.com/unclecheese/silverstripe-display-logic/pull/58) which enables fields inside of the FieldGroup to be a master.

### FieldGroup Nested Fields Logic
Again because of the same inconsistency in how FieldGroup's add fields to the rendered DOM they are missing the necessary classes to show and hide fields via display logic. This module includes a change that applies only the display logic styles to the wrapping div for each field in a FieldGroup. This allows the fields to be shown or hidden based on other fields in the form.

### Clearing Display Logic Criteria
Ever have a subclass that you need to clear the criteria on a field? Well there's a method added to all fields called ``clearDisplayLogicCriteria`` that does just that. It also returns an instance of the field so you can chain off it to call other methods on the field such as start building a new rule set.

### Ending Logic Groups
If you've ever needed multiple logic groups you'll notice that there's no way to end the group you are currently working in. Well there's a method that will allow you to do this, simply call ``endGroup`` to end the current logic group and return to it's parent logic criteria.
