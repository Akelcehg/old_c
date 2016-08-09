<div class="input-group col-xs-12 col-sm-3">
    <span class="input-group-addon"><i class="fa fa-search"></i>
    </span>
    <input class="form-control" placeholder="Filter" type="text" 
    	data-searchfor='<?php echo isset($searchfor)?json_encode($searchfor):json_encode(array()); ?>'
    >
</div>