<?php get_header(); ?>
<div class="container pad-top">
    <section class="todo">
        <ul class="todo-controls">
            <li><a href="javascript:void(0);" class="icon-add">Add</a></li>
            <li><a href="javascript:void(0);" class="icon-delete">Delete</a></li>
            <li class="right"><a href="javascript:void(0);" class="icon-settings">Settings</a></li>
        </ul>

        <ul class="todo-list">
            <li class="done">
                <input type="checkbox" id="find" checked disabled/> 
                <label class="toggle" for="find"></label>
                Create Breakdown
            </li>
            <li>
                <input type="checkbox" id="build"/> 
                <label class="toggle" for="build"></label>
                Attach to Asana
            </li>
            <li>
                <input type="checkbox" id="ship"/> 
                <label class="toggle" for="ship"></label>
                Create User/Auth Controller<br />
                Login Modal and Ajax Handler
            </li>
            <li>
                <input type="checkbox" id="ship"/> 
                <label class="toggle" for="ship"></label>
                Make Todo List Live
            </li>
        </ul>

        <ul class="todo-pagination">
            <li class="previous"><span><i class="icon-previous"></i> Previous</span></li>
            <li class="next"><a href="javascript:void(0);">Next <i class="icon-next"></i></a></li>
        </ul>
    </section>
</div>
<?php get_modal(); ?>
<?php get_footer(); ?>