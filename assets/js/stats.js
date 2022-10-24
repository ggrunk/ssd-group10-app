$( document ).ready(function() {
    console.log('stats.js ready');
    
    // initialize table
    var table = new Tabulator("#stats-table", {
        ajaxURL: base_url+"index.php?/Home/getSubmissions", //set url for ajax request
        //data:submissions,           //assign data to table
        autoColumns:true,           //create columns from data field names
        // autoColumnsDefinitions:function(definitions){
        //     //definitions - array of column definition objects
    
        //     definitions.forEach((column) => {
        //         if(column.field=="Term"){
        //             column.headerFilter = true; // add header filter to term
        //         }
                
        //     });
    
        //     return definitions;
        // },
        pagination:true,         //paginate the data
        paginationSize:10,
        paginationSizeSelector:[10, 25, 50, 100],
        //maxHeight:"700px",
        placeholder:"No Data Available", //display message to user on empty table
        searchingPlaceholder:"Filtering ...", //set the search placeholder
        emptyPlaceholder:"(no matching results found)", //set the empty list placeholder
    });
    
    
    
    $('#button-export-xlsx').click(function(){
        table.download("xlsx", "Mohawk Quality Metrics Submissions "+new Date().getFullYear()+".xlsx", {sheetName:"SubmissionData"});
    })
    
    
    var columns = [];
    for (let x in submissions_columns) {
        console.log('adding: '+submissions_columns[x]['COLUMN_NAME']);
        columns[x] = {
            title: "Name"+x,
            field: submissions_columns[x]['COLUMN_NAME']
        }
        //columns[x] = submissions_columns[x]['COLUMN_NAME'];
    }
    
    console.log('stats.js: raw submissions_columns = ');
    console.log(submissions_columns);
    console.log('stats.js: columns = ');
    console.log(columns);
    
    
    
    // import {FilterModule} from 'tabulator-tables';
    //Define variables for input elements
    var filterYear = document.getElementById("filter-year");
    var filterTerm = document.getElementById("filter-term");
    var filterCourse = document.getElementById("filter-course");
    
    
    //Trigger setFilter function with correct parameters
    function updateFilter(){
        table.clearFilter();
        filters=[];
        
        var yearVal = filterYear.value;
        if(yearVal!==""){filters.push({field:"Year", type:"=", value:yearVal});}
        //console.log(yearVal);
      
        var termVal = filterTerm.options[filterTerm.selectedIndex].value;
        if(termVal!==""){filters.push({field:"Term", type:"=", value:termVal});}
        //console.log(termVal);
        
        var courseVal = filterCourse.options[filterCourse.selectedIndex].value;
        if(courseVal!==""){filters.push({field:"Course Code", type:"=", value:courseVal});}
        //console.log(courseVal);
        
        table.setFilter(filters);
        table.refreshFilter();
    }
    
    //Update filters on value change
    document.getElementById("filter-year").addEventListener("change", updateFilter);
    document.getElementById("filter-term").addEventListener("change", updateFilter);
    document.getElementById("filter-course").addEventListener("change", updateFilter);
    // document.getElementById("filter-value").addEventListener("change", updateFilter);
    
    // Clear filters on "Clear Filters" button click
    document.getElementById("filter-clear").addEventListener("click", function(){
      filterYear.value = "";
      filterTerm.value = "";
      filterCourse.value = "";
    
      table.clearFilter();
    });
    
});