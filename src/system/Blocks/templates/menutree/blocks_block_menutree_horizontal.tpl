<div class="menutree_horizontal_container">
    {menutree data=$menutree_content id='menu'|cat:$blockinfo.bid class='menutree_horizontal z-clearfix' ext=true}
    {if $menutree_editlinks}
    <ul class="menutree_horizontal_controls">
        <li><a href="{modurl modname=Blocks type=admin func=modify bid=$blockinfo.bid addurl=1}#menutree_tabs" title="{gt text='Add the current URL as new link in this block'}">{img modname='core' set='icons' src='extrasmall/edit_add.gif' __alt='Add the current URL as new link in this block' __title='Add the current URL as new link in this block'}</a></li>
        <li><a href="{modurl modname=Blocks type=admin func=modify bid=$blockinfo.bid fromblock=1}" title="{gt text='Edit this block'"}">{img modname='core' set='icons' src='extrasmall/xedit.gif' __alt='Edit this block' __title='Edit this block'}</a></li>
    </ul>
    {/if}
</div>