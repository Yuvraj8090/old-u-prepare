@extends('layouts.website')

@section('content')

 <!-- ======= Breadcrumbs ======= -->
    <section id="breadcrumbs" class="breadcrumbs">
      <div class="container">

        <div class="d-flex justify-content-between align-items-center">
          <h2>Contact</h2>
          <ol>
            <li><a href="{{ asset('/') }}">Home</a></li>
            <li>Contact</li>
          </ol>
        </div>

      </div>
    </section><!-- End Breadcrumbs -->

    <!-- ======= Contact Us Section ======= -->
    <section id="contact-us" class="contact-us">
      <div class="container">

        <div>
         <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3442.5443679801174!2d78.08280947512553!3d30.363896474764264!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3908d70048644c07%3A0xa0a0da3e097c93a4!2sUSDMA%20New%20Building%20IT%20park!5e0!3m2!1sen!2sin!4v1714287848998!5m2!1sen!2sin" width="100%" height="450" style="border:0; width: 100%; height: 270px;"  allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>

        <div class="row mt-5">
            <h3> &nbsp; &nbsp; USDMA NEW BUILDING</h3>
            <br><br>
          <div class="col-lg-4">
            <div class="info">
              <div class="address">
                <i class="bi bi-geo-alt"></i>
                <h4>Location:</h4>
                <p>IT Park, Dehradun,Uttarakhand,248013</p>
              </div>

              <div class="email">
                <i class="bi bi-envelope"></i>
                <h4>Email:</h4>
                <p>preparegrievance@gmail.com</p>
              </div>

              <div class="phone">
                <i class="bi bi-phone"></i>
                <h4>Call:</h4>
                <p>00000000</p>
              </div>

            </div>

          </div>

          <div class="col-lg-8 mt-5 mt-lg-0">

            <form action="forms/contact.php" method="post" role="form" class="php-email-form">
              <div class="row">
                <div class="col-md-6 form-group">
                  <input type="text" name="name" class="form-control" id="name" placeholder="Your Name" required>
                  <div class="validate"></div>
                </div>
                <div class="col-md-6 form-group mt-3 mt-md-0">
                  <input type="email" class="form-control" name="email" id="email" placeholder="Your Email" required>
                  <div class="validate"></div>
                </div>
              </div>
              
                <div class="form-group mt-3">

                 <select class="form-control" >
                            <option value=""> QUERY</option>
                            <option>INQUIRY</option>
                            <option>GRIEVANCES</option>
                            <option>FEEDBACK</option>
                            <option>OTHERS</option>
                 </select>
                <div class="validate"></div>
              </div>
              
              
                        
                        
              <div class="form-group mt-3">
                <input type="text" class="form-control" name="subject" id="subject" placeholder="Subject" data-rule="minlen:4" required>
                <div class="validate"></div>
              </div>
              <div class="form-group mt-3">
                <textarea class="form-control" name="message" rows="5" placeholder="Message" required></textarea>
                <div class="validate"></div>
              </div>
              
              
              <div class="row">
                    <div class='col-md-6 form-group'>
                                <label class="label" ><b>ATTACH ID PROOF</b></label>
                                <input type="file" class="form-control"  >
                    </div>
                    <div class='col-md-6 form-group'>
                                   <label class="label" ><b>ATTACH DOCUMENT</b></label>
                                   <input type="file" class="form-control"  >
                    </div>
               </div>
              
              
              
              <div class="my-3">
                <div class="loading">Loading</div>
                <div class="error-message"></div>
                <div class="sent-message">Your message has been sent. Thank you!</div>
              </div>
              <div class="text-center"><button type="submit">Send Message</button></div>
            </form>

          </div>

        </div>

      </div>
    </section>
    

@stop