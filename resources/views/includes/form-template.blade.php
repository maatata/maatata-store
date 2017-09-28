<!-- text field template -->
<script type="text/ng-template" id="lw-form-text.ngtemplate" >
    <div class="form-group lw-remove-transclude-tag">
        <label for="[[ formField[fieldFor]['fieldFor'] ]]" class="[[ formField[fieldFor]['labelClass'] ]]">
            [[ formField[fieldFor]['label'] ]]
        </label>
            <ng-transclude class="[[ formField[fieldFor]['inputClass'] ]]"></ng-transclude>
        <div ng-messages="lwFormData.formCtrl[ formField[fieldFor]['fieldFor'] ]['$error']"
         ng-class="{ 'lw-dirty' : ( lwFormData.formCtrl[ formField[fieldFor]['fieldFor'] ]['$dirty'] || lwFormData.formCtrl.submitted ) }" ng-include src="'lw-form-validation-template.html'">
        </div>
    </div>
</script>
<!-- /text field template -->

<script type="text/ng-template" id="lw-form-selectize.ngtemplate">
    <div>
        <label for="[[ formField[fieldFor]['fieldFor'] ]]" class="[[ formField[fieldFor]['labelClass'] ]]">
            [[ formField[fieldFor]['label'] ]]
        </label>
       <ng-transclude></ng-transclude>
        <!-- include field validation template -->
        <div ng-messages="lwFormData.formCtrl[ formField[fieldFor]['fieldFor'] ]['$error']"
         ng-class="{ 'lw-dirty' : ( lwFormData.formCtrl[ formField[fieldFor]['fieldFor'] ]['$dirty'] || lwFormData.formCtrl.submitted ) }" ng-include src="'lw-form-validation-template.html'">
        </div>
    </div>
</script>

<!-- checkbox field template -->
<script type="text/ng-template" id="lw-form-checkbox-field.ngtemplate">
    <div class="form-group">
    	<ng-transclude class="[[ formField[fieldFor]['inputClass'] ]]"></ng-transclude>
        <label for="[[ formField[fieldFor]['fieldFor'] ]]" class="[[ formField[fieldFor]['labelClass'] ]]">
            [[ formField[fieldFor]['label'] ]]
        </label>
        <div ng-messages="lwFormData.formCtrl[ formField[fieldFor]['fieldFor'] ]['$error']"
         ng-class="{ 'lw-dirty' : ( lwFormData.formCtrl[ formField[fieldFor]['fieldFor'] ]['$dirty'] || lwFormData.formCtrl.submitted ) }" ng-include src="'lw-form-validation-template.html'">
        </div>
    </div>
</script>
<!-- /checkbox field template -->

<!-- radio button field template -->
<script type="text/ng-template" id="lw-form-radio-field.ngtemplate">
    <div class="form-group">
        <ng-transclude class="[[ formField[fieldFor]['inputClass'] ]]"></ng-transclude>
        <div ng-messages="lwFormData.formCtrl[ formField[fieldFor]['fieldFor'] ]['$error']"
         ng-class="{ 'lw-dirty' : ( lwFormData.formCtrl[ formField[fieldFor]['fieldFor'] ]['$dirty'] || lwFormData.formCtrl.submitted ) }" ng-include src="'lw-form-validation-template.html'">
        </div>
    </div>
</script>
<!-- /radio button field template -->

<!-- form field validation errors template help-block-->
<script type="text/ng-template" 
		id="lw-form-validation-template.html">
        <span class="text-danger lw-error-message" ng-message="required">
          [[ formField[fieldFor]['vLabel'] ? getValidationMsg('required', formField[fieldFor]['vLabel']) : getValidationMsg('required', formField[fieldFor]['label']) ]]
        </span>
        <span class="text-danger lw-error-message" ng-message="email">
            [[ formField[fieldFor]['vLabel'] ? getValidationMsg('email', formField[fieldFor]['vLabel']) : getValidationMsg('email', formField[fieldFor]['label']) ]]
        </span>
        <span class="text-danger help-block lw-error-message" ng-message="number">
          [[ formField[fieldFor]['vLabel'] ? getValidationMsg('numeric', formField[fieldFor]['vLabel']) : getValidationMsg('numeric', formField[fieldFor]['label']) ]]
        </span>
        <span class="text-danger lw-error-message" ng-message="minlength">
          [[ formField[fieldFor]['vLabel'] ? getSizeValidationMsg('min', formField[fieldFor]['vLabel']) : getSizeValidationMsg('min', formField[fieldFor]['label']) ]]
        </span>
        <span class="text-danger lw-error-message" ng-message="maxlength">
          [[ formField[fieldFor]['vLabel'] ? getSizeValidationMsg('max', formField[fieldFor]['vLabel']) : getSizeValidationMsg('max', formField[fieldFor]['label']) ]]
        </span>
        <span class="text-danger lw-error-message" ng-message="min">
          [[ formField[fieldFor]['vLabel'] ? getMinMaxValidationMsg('min', formField[fieldFor]['vLabel']) : getMinMaxValidationMsg('min', formField[fieldFor]['label']) ]]
        </span>
        <span class="text-danger lw-error-message" ng-message="max">
          [[ formField[fieldFor]['vLabel'] ? getMinMaxValidationMsg('max', formField[fieldFor]['vLabel']) :  getMinMaxValidationMsg('max', formField[fieldFor]['label']) ]]
        </span>
        <span class="text-danger lw-error-message" ng-message="unique">
          Value name should be unique for the option.
        </span>
        <span class="text-danger lw-error-message" ng-message="server">
         [[ lwFormData.formCtrl[ formField[fieldFor]['fieldFor'] ]['$error']['server'] ]]
        </span>
</script>
<!-- /form field validation errors template -->