<div ng-controller="ManageProductUploadedMediaController as uploadedMediaCtrl" class="lw-dialog">
    <!-- main heading -->
    <div class="lw-section-heading-block">
        <h3 class="lw-header"><?= __('Uploaded Files') ?></h3>
    </div>
    <!-- /main heading -->
	<!-- table -->
    <div class="table-responsive" ng-if="uploadedMediaCtrl.image_files.length > 0">
        <table class="table table-bordered custom-table">
        	<!-- thead -->
            <thead>
                <tr>
                    <th class="check" align="center" valign="top">
                        <input type="checkbox" ng-model="uploadedMediaCtrl.all_files_selected" ng-change="uploadedMediaCtrl.selectAll()">
                    </th>
                    <th><?=  __('Preview')  ?></th>
                    <th><?=  __('File Name')  ?></th>
                    <th><?=  __('Action')  ?></th>
                </tr>
            </thead>
            <!-- /thead -->
            <!-- tbody -->
            <tbody>
                <tr ng-repeat="imageFile in uploadedMediaCtrl.image_files track by $index">
                    <td class="td-check">
                        <input type="checkbox" name="checkbox" ng-model="uploadedMediaCtrl.image_files[$index]['exist']" ng-change="uploadedMediaCtrl.select()">
                    </td>
                    <td>
                        <span ng-if="imageFile.path" class="cw-image-thumb">
                            <img class="lw-image-thumbnail" ng-src="[[ imageFile.path ]]"></span>
                    </td>
                    <td class="longtext">[[ imageFile.name ]]</td>
                    <td>
                        <a href ng-click="uploadedMediaCtrl.delete(imageFile.name)" class="delete-temp-file" title="<?= __('Delete') ?>"><span class="fa fa-trash-o fa-lg"></span></a>
                    </td>
                </tr>
                <tr ng-if="uploadedMediaCtrl.image_files.length > 0">
                    <td colspan="4">
                        <a href title="<?= __('Delete Multiple Files') ?>" class="btn btn-danger btn-xs" ng-click="uploadedMediaCtrl.deleteMultipleFiles(tempUploadedFiles)"><span class="fa fa-trash-o fa-lg"></span> <?= __('Delete') ?> </a></td>
                </tr>
            </tbody>
			<!-- /tbody -->
        </table>
	</div>
    <!-- table -->
    <div ng-if="uploadedMediaCtrl.image_files.length == 0" class="alert alert-info">
        <?= __( 'There are no files to display.' ) ?>
    </div>
    <!-- button action -->
    <div>
        <button class="btn btn-default" title="<?= __( 'Close' ) ?>" ng-click="uploadedMediaCtrl.close()"><?= __( 'Close' ) ?></button>
    </div>
	<!-- /button action -->
</div>
