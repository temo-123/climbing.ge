@if(isset($star_rewiev))
    <div class="span3 well">
        <center>
        <a href="#aboutModal" data-toggle="modal" data-target="#star_rewiev">
        <h3>Add New Rewiev</h3>
        </a>
        </center>
    </div>   

    <!-- Modal -->
    <div class="modal fade" id="star_rewiev" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title" id="myModalLabel">More About Joe</h4>
                    </div>
                <div class="modal-body">
                    <main>
                    <section>
                    <!-- form starts -->
                    <form name="star_submit" id="star_submit" action="{{route('starReviewAdd', array('article_id'=>$article->id, 'action'=>'add', 'category'=>'article_review'))}}" method="post">
                        @csrf
                    <legend> Star Rating</legend>

                         <label>Rate this form's subject: </label> 
                                <br>
                       <div class="star_rating">
                          <!-- this has 4 stars, you can change to add or subtract stars. -->
                             <input type="checkbox" id="star5" name="Star_Rating_of_5_stars" class="star" value="5">
                          <label for="star5" class="star" title="5 stars"></label>
                            <input type="checkbox" id="star4" name="Star_Rating_of_5_stars" class="star" value="4">
                          <label for="star4" class="star" title="4 stars"></label>
                             <input type="checkbox" id="star3" name="Star_Rating_of_5_stars" class="star" value="3">
                          <label for="star3" class="star" title="3 stars"></label>
                             <input type="checkbox" id="star2" name="Star_Rating_of_5_stars" class="star" value="2">
                          <label for="star2" class="star" title="2 stars"></label>
                             <input type="checkbox" id="star1" name="Star_Rating_of_5_stars" class="star" value="1">
                          <label for="star1" class="star" title="1 stars"></label>
                       </div>

                          <!-- submit or form reset buttons -->
                          <div class="rectangle">
                             <label for="submit">
                                <button type="submit" id="submit" class="reset" value="Submit">Submit</button>
                             </label>
                             <label for="reset">
                                <button type="reset" id="reset" class="reset" value="Reset">Reset</button>
                             </label>
                          </div>
                    </form>
                    </section>
                    </main>





                       <style type="text/css">


                          /** buttons */
                          .box {
                             border: 1pt solid var(--accent-bg);
                             padding: 0.5em;
                             margin-bottom: 0.4em;
                          }
                          .rectangle {
                             background-color: var(--accent-bg);
                             padding: 0.5em;
                          }
                          .rounded, .reset {
                             border: 2px solid var(--accent-color);
                             border-radius: 7px;
                             text-align: center;
                             font-weight: bold;
                             padding: 0.5em 0 0.5em 0;
                             margin: 0.3em;
                             min-width: 9em;
                             height: 3em;
                          }
                          .reset, .rounded:hover {
                             background-color: transparent;
                             color: var(--accent-color);
                             border-color: var(--accent-color);
                          }
                          .rounded, .reset:hover {
                             background-color: var(--accent-color);
                             color: white;
                             border-color: white;
                          }
                          button:hover {
                             box-shadow: 2px 2px white;
                          }

                          /* -- star rating styling -- */
                          div.star_rating {
                             float: left;
                          }

                          input.star {
                             border: 0;
                             width: 1px;
                             height: 1px;
                             overflow: hidden;
                             position: absolute !important;
                             clip: rect(1px 1px 1px 1px);
                             clip: rect(1px, 1px, 1px, 1px);
                             opacity: 0;
                             float: right;
                          }

                          label.star {
                             position: relative;
                             float: right;
                             color: #C8C8C8;
                          }

                          label.star:before {
                             margin: 5px;
                             content: "\f005";
                             font-family: FontAwesome;
                             font-size: 1.5em;
                             color: var(--accent-color);
                             -webkit-user-select: none; /* Safari 3.1+ */
                             -moz-user-select: none; /* Firefox 2+ */
                             -ms-user-select: none; /* IE 10+ */
                             user-select: none;
                          }

                          input.star:checked ~ label.star:before {
                             color: #FFC107;
                          }

                          label.star:hover ~ label.star:before {
                             color: #ffdb70;
                          }

                          label.star:hover:before {
                             color: #FFC107;
                          }
                       </style>

                </div>
            </div>
        </div>
    </div>

@endif