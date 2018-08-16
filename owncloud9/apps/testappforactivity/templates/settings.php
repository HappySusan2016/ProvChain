<?php if(basename($_SERVER['REQUEST_URI'])=='admin'): ?>
<form id="superlogsettings" method="post" action="#superlogsettings">
    <fieldset class="personalblock">
    	
    	<legend><strong>SuperLog</strong></legend>
	        <p>
	            <label for="superlog_lifetime"><?php echo $l->t('Logs life time:');?>
	            <input type="number" id="superlog_lifetime" name="superlog_lifetime" value="<?php echo $_['superlog_lifetime']; ?>" size="3"/> 
	            <?php echo $l->t('days');?>
	            </label>         
	        </p>      
        <input type="submit" value="<?php echo $l->t('Save');?>" /> 
	</fieldset> 
</form> 
<?php endif; ?>
    <fieldset id="superlog"  class="personalblock">   
        <legend><strong>SuperLog <?php echo $l->t('Activity');?></strong></legend>  
        <div id="superlog_filter">
        	<span id="superlog_filters"><?php echo $l->t('Filters');?></span>
        	<input type="hidden" value="0" id="superlog_start"/>
        	<label for="superlog_since">
        		<?php echo $l->t('Since:');?>
        		<input type="datetime" value="" id="superlog_since" size="20"/>
        	</label>
        	<label for="superlog_to">
        		<?php echo $l->t('To:');?>
        		<input type="datetime" value="" id="superlog_to" size="20"/>
        	</label>
        	<label for="superlog_search">
        		<?php echo $l->t('Search:');?>
        		<input type="text" value="" id="superlog_search"/>
        	</label>
        	<label for="superlog_type">
        		<?php echo $l->t('Filetype:');?>
        		<select id="superlog_type">
        			<option value=""><?php echo $l->t('All');?></option>
        			<option value="file"><?php echo $l->t('Files');?></option>
        			<option value="dir"><?php echo $l->t('Directories');?></option>	
        		</select>
        	</label>
        	<label for="superlog_action">
        		<?php echo $l->t('Action:');?>
        		<select id="superlog_action">
        			<option value=""><?php echo $l->t('All');?></option>
        			<option value="write"><?php echo $l->t('Write');?></option>
        			<option value="delete"><?php echo $l->t('Delete');?></option>	
        			<option value="move"><?php echo $l->t('Move');?></option>		
        			<option value="rename"><?php echo $l->t('Rename');?></option>	
        		</select>
        	</label>
        	<label for="superlog_protocol">
        		<?php echo $l->t('Protocol:');?>
        		<select id="superlog_protocol">
        			<option value=""><?php echo $l->t('All');?></option>
        			<option value="web"><?php echo $l->t('Web interface');?></option>
        			<option value="webdav"><?php echo $l->t('WebDav access');?></option>
        			<option value="carddav"><?php echo $l->t('CardDav access');?></option>
        			<option value="caldav"><?php echo $l->t('CalDav access');?></option>
        		</select>
        	</label>
        </div>
        <table>
        	<thead>
        		<tr>
        			<th><?php echo $l->t('User');?></th>
        			<th><?php echo $l->t('Activity');?></th>
        			<th><?php echo $l->t('Date');?></th>
        		</tr>
        	</thead>
        	<tbody id="superlogs_results">
        		
        	</tbody>
        </table>  
        <input type="button" id="superlog_more" value="<?php echo $l->t('More...');?>"/>   
    </fieldset>
