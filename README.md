<p align="center">
    <h1 align="center">CPT with pagination and filtering by Ajax for WordPress</h1>
    <br>
</p>

<p><strong>Task for WordPress developer</strong></p>
<p>
    <ol>
        <li>Create a custom post type News</li>
        <li>For the post type News, you need to add a taxonomy with no
           less than 5 terms</li>
        <li>Add at least 15 records of type News to the site,
           moreover, some (at least 5) records must be attributed to one
           created term,
           some (minimum 3) to two or more terms, and sotal without any
           terma accessories.</li>
        <li>Implement a page that will display all records of the type
           News, with the ability to filter them by terms.</li>
        <li>The filter should work without reloading the page, and also
           pagination must be present (no more than 5 entries per page).</li>
        <li>During filtering, provide the ability to select more than
           one term (display records from all specified terms),
           as well as resetting the filter to default (nothing selected)</li>
        <li>The appearance of the filter should be neat and adaptive (for
           devices: 320px - 1920px)</li>
    </ol>
</p>

<p><strong>Solution of the task in the form of a plug-in</strong></p>
<p>Custom post type with pagination and ajax filtering. After activating the plugin and filling the custom post type with data to display them in the front of the site, you need to add a shortcode to the page or directly to the code.</p>
<p>
     Usage page: <br>
     <pre>[amid_ajax_filter_cpt per_page="5"]</pre>
     Template file: <br>
     <pre>do_shortcode('[amid_ajax_filter_cpt per_page="5"]')</pre>
</p>

<p>Demo example of the plugin working on the default WordPress theme<br>
<a href="https://new.maze.sbs/novosti" target="_blank">Ajax filtering with pagination</a>
</p>