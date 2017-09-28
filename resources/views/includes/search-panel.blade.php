<div class="panel panel-default ">
            <div class="panel-heading hidden-xs">
                <h3 class="panel-title"><?= __("Search") ?></h3>
            </div>
            <div class="list-group visible-xs">
              <a class="list-group-item lw-sidebar-menu-toggle-btn" href data-toggle="offcanvas"><i class="sidebar icon"> </i> <i class="fa fa-bars"></i> <?=  __( 'Menu' )  ?></a>
            </div>
            <div class="panel-body">
                <form method="get" action="<?=  route('product.search')  ?>">
                 @if(isset($searchTerm))
                  <div class="row input-search">
                    <div class="col-md-12">
                      <div class="input-group">
                        <input type="text"  name="search_term" class="form-control lw-z-index col-lg-4" value="<?=  $searchTerm  ?>" placeholder="<?= __('Search Products') ?>">
                        <span class="input-group-btn">
                          <button style="z-index: 0;" type="submit" title="<?=  __('Go')  ?>" class="btn btn-primary">
                            <?=  __('Go')  ?>
                          </button>
                        </span>
                      </div>
                    </div>
                  </div>
                  @else 
                  <div class="row input-search">
                    <div class="col-md-12">
                      <div class="input-group">
                        <input type="text"  name="search_term" class="form-control lw-z-index col-lg-4" value="" ng-model="search.searchtext" placeholder="<?= __('Search Products') ?>">
                        <span class="input-group-btn">
                          <button style="z-index: 0;" ng-disabled="!search.searchtext" type="submit" title="<?=  __('Go')  ?>" class="btn btn-primary">
                            <?=  __('Go')  ?>
                          </button>
                        </span>
                      </div>
                    </div>
                  </div>
                  @endif
                </form>
            </div>
        </div>