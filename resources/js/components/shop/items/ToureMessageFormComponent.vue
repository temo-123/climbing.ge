<template>
    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 mb30 text-center">
                    <h2>Tour & Travel Reservation Booking Form</h2>
                </div>
            </div>
            <div class="row">

                <div class="col-xl-12 col-lg-12 col-md-12 mb30">
                    <div class="tour-booking-form">
                        <form>
                            <div class="row">
                                <div class="col-xl-6 col-lg-6 col-md-12">
                                    <div class="form-group">
                                        <label class="control-label" for="datepicker">Check in</label>
                                        <div class="select">
                                            <input id="datepicker" name="datepicker" type="date" placeholder="Date" class="form-control" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-12">
                                    <div class="form-group">
                                        <label class="control-label required" for="select">Number of Persons:</label>
                                        <div class="select">
                                            <select id="select" name="select" class="form-control">
                                                <option value="">Number of Persons:</option>
                                                <option value="">01</option>
                                                <option value="">02</option>
                                                <option value="">03</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-xl-6 col-lg-6 col-md-12">
                                    <div class="form-group">
                                        <label class="control-label" for="name">Name</label>
                                        <input id="name" type="text" placeholder="First Name" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-12">
                                    <div class="form-group">
                                        <label class="control-label" for="email"> Email</label>
                                        <input id="email" type="text" placeholder="XXXX@XXXX.XXX" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-12">
                                    <div class="form-group">
                                        <label class="control-label" for="phone"> Phone</label>
                                        <input id="phone" type="text" placeholder="(XXX) XXX-XXXXX" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-12">
                                    <div class="form-group">
                                        <label class="control-label" for="country">Country</label>
                                        <input id="country" type="text" placeholder="India" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-12">
                                    <div class="form-group">
                                        <label class="control-label" for="city">City</label>
                                        <input id="city" type="text" placeholder="Ahmedabad" class="form-control" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xl-12 col-lg-12 col-md-12">
                                    <div class="form-group">
                                        <label class="control-label" for="textarea">Describe Your Travel Requirements</label>
                                        <textarea class="form-control" id="textarea" name="textarea" rows="4" placeholder="Write Your Requirements"></textarea>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</template>

<script>

</script>

<style lang="scss" scoped>

</style>