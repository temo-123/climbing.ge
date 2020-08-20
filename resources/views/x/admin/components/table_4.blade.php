

<div class=" bootstrap snippet">
table 4
        @if(isset($table_4_add_url))
        <div style='margin: 20px 0 75px 0; '>
            <a href="{{route($table_4_add_url)}}" class="btn btn-primary pull-left" type="submit">New - {{$table_4_name}}</a>
        </div>
        @endif
            
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">Articles</h3>
                        <div class="pull-right">
                            <span class="clickable filter" data-toggle="tooltip" title="Toggle table filter" data-container="body">
                                <i style="margin-top: -40%;" class="glyphicon glyphicon-search"></i>
                            </span>
                        </div>
                    </div>
                    <div class="tab-pane active" id="table_4">
                        <div class="table-responsive"><!-- table move -->
                            <div class="panel-body">
                                <input type="text" class="form-control" id="dev-table-filter" data-action="filter" data-filters="#dev-table" placeholder="Filter Developers" />
                            </div>
                            <table class="table table-hover" id="dev-table">
                                <thead>
                                    <tr>
                                        <th style='text-align: center;'>
                                            <label><input type="checkbox" class="all"></label>
                                        </th>
                                        <th>|</th>
                                        <th>ID</th>
                                        <th>|</th>
                                        <th>Name</th>
                                        <th>|</th>
                                        <th style='text-align: center;'>Published</th>
                                        <th>|</th>
                                        <th>Edit</th>
                                        <th>|</th>
                                        <th>Delite</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($table_4 as $table4)
                                    <tr>
                                        <td style='text-align: center;'>
                                            <label><input type="checkbox"></label>
                                        </td>
                                        <th>|</th>
                                        <td>{{$table4 -> id}}</td>
                                        <th>|</th>

                                        @if(isset($table4 -> title))
                                        <td>{{$table4 -> title}}</td>
                                        @elseif(isset($table4 -> name))
                                        <td>{{$table4 -> name}}</td>
                                        @else
                                        <td>Error</td>
                                        @endif

                                        <th>|</th>
                                        <td style='text-align: center;'>{{$table4 -> published}}</td>
                                        <th>|</th>
                                        <td>
                                            <a href="{{route($table_4_edit_url,['outdoor'=>$table4->id])}}" class="btn btn-default" type="submit">Edit</a>
                                        </td>
                                        <th>|</th>
                                        <td>
                                            <form action="{{route($table_4_edit_url,['outdoor'=>$table4->id])}}" method="post" class="form-horisontal">
                                                <button class="btn btn-default" type="submit">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
                     
                     
                     
                     
                     





    <style type="text/css">
        .panel-primary {
            border-color: #b3b3b3;
        }
                .row{
            /*margin-top:40px;*/
            /*padding: 0 10px;*/
        }
        .clickable{
            cursor: pointer;   
        }

        .panel-heading div {
            margin-top: -18px;
            font-size: 15px;
        }
        .panel-heading div span{
            margin-left:5px;
        }
        .panel-body{
            display: none;
        }
    </style>

    <script type="text/javascript">

(function(){
    'use strict';
    var $ = jQuery;
    $.fn.extend({
        filterTable: function(){
            return this.each(function(){
                $(this).on('keyup', function(e){
                    $('.filterTable_no_results').remove();
                    var $this = $(this), 
                        search = $this.val().toLowerCase(), 
                        target = $this.attr('data-filters'), 
                        $target = $(target), 
                        $rows = $target.find('tbody tr');
                        
                    if(search == '') {
                        $rows.show(); 
                    } else {
                        $rows.each(function(){
                            var $this = $(this);
                            $this.text().toLowerCase().indexOf(search) === -1 ? $this.hide() : $this.show();
                        })
                        if($target.find('tbody tr:visible').size() === 0) {
                            var col_count = $target.find('tr').first().find('td').size();
                            var no_results = $('<tr class="filterTable_no_results"><td colspan="'+col_count+'">No results found</td></tr>')
                            $target.find('tbody').append(no_results);
                        }
                    }
                });
            });
        }
    });
    $('[data-action="filter"]').filterTable();
})(jQuery);

$(function(){
    // attach table filter plugin to inputs
    $('[data-action="filter"]').filterTable();
    
    $('.container').on('click', '.panel-heading span.filter', function(e){
        var $this = $(this), 
            $panel = $this.parents('.panel');
        
        $panel.find('.panel-body').slideToggle();
        if($this.css('display') != 'none') {
            $panel.find('.panel-body input').focus();
        }
    });
    $('[data-toggle="tooltip"]').tooltip();
})
    </script>





                            <script type="text/javascript">
                              $('.all').on('click', function(e){
                                $this = this;  
                                $.each($(this).parents('table').find('input'), function(i, item){
                                  $(item).prop('checked', $this.checked);
                                });
                              });
                            </script>





























