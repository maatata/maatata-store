<?php 
	function buildNevigationMenu($menuData) {

		$pageNevMarkup = '';

		foreach ($menuData as $page) {

	        $title  = $page['name'];
	        $pageID = $page['id'];

	        $path 		= (Request::url()) == $page['link'] ? 'active' : '';
	        $targetType = ($page['target'] == '_blank') ? '' : 'lw-show-process-action';

	        if (!empty($page['children'])) {

	            // this section contain children's 
	            $pageNevMarkup .= "<li class='".$path."'><a class='".$targetType."' target='".$page['target']."' href='".$page['link']."'>".$title."</a><ul>";

	            $pageNevMarkup .= buildNevigationMenu($page['children']);

	            $pageNevMarkup .= "</ul></li>";

	        } else {

	        	if (isLoggedIn()) {

	        		if ($page['id'] !== getSysLinkId('login') 
	        			and $page['id'] !==  getSysLinkId('register')) {

	        			$pageNevMarkup .= "<li class='".$path."'><a class='".$targetType."' href='".$page['link']."' target='".$page['target']."'>".$title."</a></li>";
	        		}

	        	} else {

					$pageNevMarkup .= "<li class='".$path."'><a class='".$targetType."' href='".$page['link']."' title='".$title."' target='".$page['target']."'>".$title."</a></li>";
	        	}
	        	
	        }
	    }

	    return  $pageNevMarkup;
	}
?>

