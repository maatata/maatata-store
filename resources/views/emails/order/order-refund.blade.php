<table cellspacing="0" cellpadding="0" width="600" class="w320" style="border-collapse: collapse !important; font-family: Helvetica, Arial, sans-serif;">
	<tbody>
		<tr style="font-family: Helvetica, Arial, sans-serif;">
			<td class="header-lg" style="border-collapse: collapse; color: #4d4d4d; font-family: Helvetica, Arial, sans-serif; font-size: 32px; font-weight: 700; line-height: normal; padding: 35px 0 0; text-align: center;">
			 <?= ( 'Order Refund Process!' ) ?>
			</td>
		</tr>
		<tr style="font-family: Helvetica, Arial, sans-serif;">
			<td class="free-text" style="border-collapse: collapse; color: #777777; font-family: Helvetica, Arial, sans-serif; font-size: 14px; line-height: 21px; padding: 10px 60px 0px; text-align: center; width: 100% !important;">                    
				@if(!empty($discription))
					<?=  $discription  ?>
				@endif
			</td>
		</tr>
		@if(!empty($additionalNotes))
			<tr style="font-family: Helvetica, Arial, sans-serif;">
	          <td class="free-text" style="background-color: #ffffff; border: 1px solid #e5e5e5; border-collapse: collapse; border-radius: 5px; color: #777777; font-family: Helvetica, Arial, sans-serif; font-size: 14px; line-height: 21px; padding: 12px 15px 15px; text-align: left; width: 253px;">
	            <span class="header-sm" style="color: #4d4d4d; font-family: Helvetica, Arial, sans-serif; 
	            font-size: 18px; font-weight: 700; line-height: 1.3; padding: 5px 0;"><?= ( 'Additional notes' ) ?></span><br style="font-family: Helvetica, Arial, sans-serif;"> 
	            <?=  $additionalNotes  ?>
	          </td>
	        </tr>
        @endif
	</tbody>
</table>
