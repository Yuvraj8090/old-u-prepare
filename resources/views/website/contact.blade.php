@extends('layouts.app')

@section('content')
 <div class="container">
            <div class="row">
                <div class="col-md-6 col-xs-12">
                  <style>
                      .box{
                         padding:30px;
                      }
                      .form-group{
                        margin-bottom: 15px;
                      }
                      .form-control{
                        background-color: #d1c8c1;
                      }
                      .form-control::placeholder{
                        font-weight: 600;
                        color: black;
                      }
                  </style>
                      <div class="box">
                          
                          <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3442.5443679801174!2d78.08280947512553!3d30.363896474764264!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3908d70048644c07%3A0xa0a0da3e097c93a4!2sUSDMA%20New%20Building%20IT%20park!5e0!3m2!1sen!2sin!4v1714287848998!5m2!1sen!2sin" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                          
                          <h1>USDMA NEW BUILDING</h1>
                          <p>
                              <b> <li class="fa fa-map-marker" ></li>  &nbsp; IT Park, Dehradun,Uttarakhand,248013</b>
                          </p>
                           <p>
                              <b> <li class="fa fa-phone" ></li> &nbsp; 00000000 </b>
                          </p>
                           <p>
                              <b>  <li class="fa fa-envelope" ></li>  &nbsp; preparegrievance@gmail.com </li> </b>
                          </p>

                      </div>
                   
                </div>
                <div class="col-md-6 col-xs-12">
                  
                 <div class="box">
                      <form action="" method="" > 
                          <div class="form-group">
                              <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="NAME">
                              <!-- <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small> -->
                          </div>
                          <div class="form-group">
                              <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="EMAIL">
                              <!-- <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small> -->
                          </div>
                          <div class="form-group">
                              <select class="form-control" >
                                    <option value=""> QUERY</option>
                                    <option>INQUIRY</option>
                                    <option>GRIEVANCES</option>
                                    <option>FEEDBACK</option>
                                    <option>OTHERS</option>
                              </select>
                            
                              <!-- <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small> -->
                          </div>
                          <div class="form-group">
                              <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="SUBJECT">
                          </div>
                          <div class="form-group">
                              <textarea rows="10" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="MESSAGE" ></textarea>
                          </div>
                        
                        <div class="row">
                            <div class='col-md-6'>
                                <label class="label" ><b>ATTACH ID PROOF</b></label>
                                <input type="file" class="form-control"  >
                            </div>
                            <div class='col-md-6'>
                                   <label class="label" ><b>ATTACH DOCUMENT</b></label>
                                   <input type="file" class="form-control"  >
                            </div>
                        </div>
                        <br>
                        
                          <div class="form-group">
                            <button type="submit" class="btn btn-lg btn-primary " >Submit</button>
                        </div>
                      </form>
                  </div>
                 
                </div>
            </div>
        </div>
@stop