<div class=" bootstrap snippet">
table 1

        @if(isset($table_1_add_url))
        <div style='margin: 20px 0 75px 0; '>
            @if(isset($table_1_add_category))
            <a href="{{route($table_1_add_url, array('category'=>$table_1_add_category))}}" class="btn btn-primary pull-left" type="submit">New - {{$table_1_name}}</a>
            @else
            <a href="{{route($table_1_add_url)}}" class="btn btn-primary pull-left" type="submit">New - {{$table_1_name}}</a>
            @endif
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
                    <div class="tab-pane active" id="table_1">
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
                                        @if(isset($table_1_edit_url))
                                        <th>|</th>
                                        <th>Edit</th>
                                        @endif
                                        @if(isset($table_1_edit_url))
                                        <th>|</th>
                                        <th>Delite</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($table_1 as $table1)
                                    <tr>
                                        <td style='text-align: center;'>
                                            <label><input type="checkbox"></label>
                                        </td>
                                        <th>|</th>
                                        <td>{{$table1 -> id}}</td>
                                        <th>|</th>

                                        @if(isset($table1 -> title))
                                        <td>{{$table1 -> title}}</td>
                                        @elseif(isset($table1 -> name))
                                        <td>{{$table1 -> name}}</td>
                                        @else
                                        <td>Error</td>
                                        @endif

                                        <th>|</th>
                                        <td style='text-align: center;'>{{$table1 -> published}}</td>
                                        @if(isset($table_1_edit_url))
                                        <th>|</th>
                                        <td>
                                            <a href="{{route($table_1_edit_url, ['outdoor'=>$table1->id])}}" class="btn btn-primary" type="submit">Edit</a>
                                        </td>
                                        @endif
                                        @if(isset($table_1_edit_url))
                                        <th>|</th>
                                        <td>
                                            <form action="{{route($table_1_edit_url, array('id'=>$table1->id))}}" method="post" class="form-horisontal">
                                                <button class="btn btn-danger" type="submit" id="51btn" class="b2" value="open" onclick="JavaScript:return Validator(this.id);">Delete</button>
                                            </form>
                                        </td>
                                        @endif
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

function Validator(id_){
   if(confirm("Вы подтверждаете операцию?") ){
     var ttt= document.getElementById(id_);
     alert(ttt.id);
      return(true);
     
   }else{
     alert("2");
      return(false);
     
   }
}










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





























