 <!-- Side-Nav-->
 <style>
     #sidebar-scroll{
        height: 100vh;
        overflow: scroll;
     }
    #sidebar-scroll::-webkit-scrollbar {
        width: 3px;
    }
    #sidebar-scroll::-webkit-scrollbar-track {
        background-color: #ffffff;
    }
    #sidebar-scroll::-webkit-scrollbar-thumb {
        background-color: #f57c00;
    }
 </style>
 <aside class="main-sidebar hidden-print ">
         <section class="sidebar" id="sidebar-scroll">
            <!-- Sidebar Menu-->
            <ul class="sidebar-menu">
                <li class="nav-level">--- ADMIN</li>
                <li class="active treeview">
                    <a class="waves-effect waves-dark" href="{{route('admin.dashboard')}}">
                        <i class="icon-speedometer"></i><span> Dashboard</span>
                    </a>                
                </li>
                <!-- <li class="nav-level">--- Components</li> -->
                <li class="treeview"><a class="waves-effect waves-dark" href="#!"><i class="fa fa-user" aria-hidden="true"></i><span> Admin</span><i class="icon-arrow-down"></i></a>
                    <ul class="treeview-menu">
                        <li><a class="waves-effect waves-dark" href="{{route('manageadmins.list')}}"><i class="icon-arrow-right"></i> Manage Admin</a></li>
                    </ul>
                </li>

                <li class="treeview"><a class="waves-effect waves-dark" href="#!"><i class="fa fa-users" aria-hidden="true"></i><span> Users</span><i class="icon-arrow-down"></i></a>
                    <ul class="treeview-menu">
                        <li><a class="waves-effect waves-dark" href="{{route('manageusers.list')}}"><i class="icon-arrow-right"></i> Manage Users</a></li>
                        <li><a class="waves-effect waves-dark" href="{{route('manageusers.create')}}"><i class="icon-arrow-right"></i> Add User</a></li>
                    </ul>
                </li>

                <li class="treeview"><a class="waves-effect waves-dark" href="#!"><i class="fa fa-files-o" aria-hidden="true"></i><span> Pages</span><i class="icon-arrow-down"></i></a>
                    <ul class="treeview-menu">
                        <li><a class="waves-effect waves-dark" href="{{route('pages.index')}}"><i class="icon-arrow-right"></i> Manage Pages</a></li>
                        <li><a class="waves-effect waves-dark" href="{{route('pages.create')}}"><i class="icon-arrow-right"></i> Add Pages</a></li>
                    </ul>
                </li>

                <li class="treeview"><a class="waves-effect waves-dark" href="#!"><i class="fa fa-bars" aria-hidden="true"></i><span>  Manage Menu</span><i class="icon-arrow-down"></i></a>
                    <ul class="treeview-menu">
                        <li><a class="waves-effect waves-dark" href="{{route('managemenu.list')}}"><i class="icon-arrow-right"></i> Manage Menu</a></li>
                        <li><a class="waves-effect waves-dark" href="{{route('managemenu.create')}}"><i class="icon-arrow-right"></i> Add Menu</a></li>
                    </ul>
                </li>

                <li class="treeview"><a class="waves-effect waves-dark" href="#!"><i class="fa fa-bank"></i><span> Manage Campaign List</span><i class="icon-arrow-down"></i></a>
                    <ul class="treeview-menu">
                        <li><a class="waves-effect waves-dark" href="{{route('managecampaign.list')}}"><i class="icon-arrow-right"></i> Campaign Manager List</a></li>
                    </ul>
                </li>

                <li class="treeview"><a class="waves-effect waves-dark" href="#!"><i class="fa fa-picture-o" aria-hidden="true"></i><span>Manage Blog</span><i class="icon-arrow-down"></i></a>
                    <ul class="treeview-menu">
                        <li><a class="waves-effect waves-dark" href="{{route('manageblog.list')}}"><i class="icon-arrow-right"></i> Manage Blog</a></li>
                        <li><a class="waves-effect waves-dark" href="{{route('manageblog.create')}}"><i class="icon-arrow-right"></i> Add Blog</a></li>
                        <li><a class="waves-effect waves-dark" href="{{route('manageblogcat.list')}}"><i class="icon-arrow-right"></i> Manage Blog Category</a></li>
                        <li><a class="waves-effect waves-dark" href="{{route('manageblogcat.create')}}"><i class="icon-arrow-right"></i> Add Blog Category</a></li>
                        <li><a class="waves-effect waves-dark" href="{{route('manageblogtag.list')}}"><i class="icon-arrow-right"></i> Manage Blog Tag</a></li>
                        <li><a class="waves-effect waves-dark" href="{{route('manageblogtag.create')}}"><i class="icon-arrow-right"></i> Add Blog Tag</a></li>
                    </ul>
                </li>

                <li class="treeview"><a class="waves-effect waves-dark" href="#!"><i class="fa fa-quote-left" aria-hidden="true"></i><span>  Manage Testimonials</span><i class="icon-arrow-down"></i></a>
                    <ul class="treeview-menu">
                        <li><a class="waves-effect waves-dark" href="{{route('managetestimonial.list')}}"><i class="icon-arrow-right"></i> Manage Testimonials</a></li>
                        <li><a class="waves-effect waves-dark" href="{{route('managetestimonial.create')}}"><i class="icon-arrow-right"></i> Add Testimonial</a></li>
                    </ul>
                </li>

                <li class="treeview"><a class="waves-effect waves-dark" href="#!"><i class="fa fa-bars" aria-hidden="true"></i><span>  Dashboard Menu</span><i class="icon-arrow-down"></i></a>
                    <ul class="treeview-menu">
                        <li><a class="waves-effect waves-dark" href="accordion.html"><i class="icon-arrow-right"></i> Dashboard Menu</a></li>
                        <li><a class="waves-effect waves-dark" href="button.html"><i class="icon-arrow-right"></i>Add Dashboard Menu</a></li>
                    </ul>
                </li>

                <li class="treeview"><a class="waves-effect waves-dark" href="#!"><i class="fa fa-home" aria-hidden="true"></i><span> Manage Party</span><i class="icon-arrow-down"></i></a>
                    <ul class="treeview-menu">
                        <li><a class="waves-effect waves-dark" href="{{route('manageparty.list')}}"><i class="icon-arrow-right"></i> Manage Party</a></li>
                        <li><a class="waves-effect waves-dark" href="{{route('manageparty.create')}}"><i class="icon-arrow-right"></i> Add Party</a></li>
                        <li><a class="waves-effect waves-dark" href=""><i class="icon-arrow-right"></i> Import Party</a></li>
                    </ul>
                </li>

                <li class="treeview"><a class="waves-effect waves-dark" href="#!"><i class="fa fa-area-chart" aria-hidden="true"></i><span> Manage Political Zones</span><i class="icon-arrow-down"></i></a>
                    <ul class="treeview-menu">
                        <li><a class="waves-effect waves-dark" href="{{route('managestate.list')}}"><i class="icon-arrow-right"></i> Manage States</a></li>
                        <li><a class="waves-effect waves-dark" href="{{route('senatorialdist.list')}}"><i class="icon-arrow-right"></i> Manage Senatorial Districts</a></li>
                        <li><a class="waves-effect waves-dark" href="{{route('federalconst.list')}}"><i class="icon-arrow-right"></i> Manage Federal Constituency</a></li>
                        <li><a class="waves-effect waves-dark" href="{{route('stateconst.list')}}"><i class="icon-arrow-right"></i> Manage State Constituency</a></li>
                        <li><a class="waves-effect waves-dark" href="{{route('managelga.list')}}"><i class="icon-arrow-right"></i> Manage Local Government Area</a></li>
                        <li><a class="waves-effect waves-dark" href="{{route('manageward.list')}}"><i class="icon-arrow-right"></i> Manage Wards</a></li>
                    </ul>
                </li>

                <li class="treeview"><a class="waves-effect waves-dark" href="#!"><i class="fa fa-file" aria-hidden="true"></i><span>Manage Site Content</span><i class="icon-arrow-down"></i></a>
                    <ul class="treeview-menu">
                        <li><a class="waves-effect waves-dark" href="{{route('managesitecontent.list')}}"><i class="icon-arrow-right"></i> Manage Site Content</a></li>
                        <li><a class="waves-effect waves-dark" href="{{route('managesitecontent.create')}}"><i class="icon-arrow-right"></i> Add Site Content</a></li>
                    </ul>
                </li>

                <li class="treeview"><a class="waves-effect waves-dark" href="#!"><i class="fa fa-building"></i><span> Manage Polling Unit</span><i class="icon-arrow-down"></i></a>
                    <ul class="treeview-menu">
                        <li><a class="waves-effect waves-dark" href="{{route('managepollings.list')}}"><i class="icon-arrow-right"></i> Manage Polling Unit</a></li>
                        <li><a class="waves-effect waves-dark" href="{{route('managepollings.create')}}"><i class="icon-arrow-right"></i> Add Polling Unit</a></li>
                    </ul>
                </li>

                <li class="treeview"><a class="waves-effect waves-dark" href="#!"><i class="fa fa-codiepie"></i><span> Social Media</span><i class="icon-arrow-down"></i></a>
                    <ul class="treeview-menu">
                        <li><a class="waves-effect waves-dark" href="{{route('managesociallinks.list')}}"><i class="icon-arrow-right"></i> Manage Social Links </a></li>
                        <li><a class="waves-effect waves-dark" href="{{route('managesociallinks.create')}}"><i class="icon-arrow-right"></i> Add Social Links </a></li>
                    </ul>
                </li>

                <li class="treeview"><a class="waves-effect waves-dark" href="#!"><i class="fa fa-bar-chart" aria-hidden="true"></i><span>Manage Feature</span><i class="icon-arrow-down"></i></a>
                    <ul class="treeview-menu">
                        <li><a class="waves-effect waves-dark" href="{{route('managefeature.list')}}"><i class="icon-arrow-right"></i> Manage Feature</a></li>
                        <li><a class="waves-effect waves-dark" href="{{route('managefeature.create')}}"><i class="icon-arrow-right"></i> Add Feature</a></li>
                    </ul>
                </li>

                <li class="treeview"><a class="waves-effect waves-dark" href="#!"><i class="fa fa-bars" aria-hidden="true"></i><span>Admin Menu</span><i class="icon-arrow-down"></i></a>
                    <ul class="treeview-menu">
                        <li><a class="waves-effect waves-dark" href="{{route('adminmenu.list')}}"><i class="icon-arrow-right"></i> Admin Menu</a></li>
                        <li><a class="waves-effect waves-dark" href="{{route('adminmenu.create')}}"><i class="icon-arrow-right"></i> Add Admin Menu</a></li>
                    </ul>
                </li>

                <li class="treeview"><a class="waves-effect waves-dark" href="#!"><i class="fa fa-user-circle-o" aria-hidden="true"></i><span> Manage Client</span><i class="icon-arrow-down"></i></a>
                    <ul class="treeview-menu">
                        <li><a class="waves-effect waves-dark" href="{{route('manageclient.list')}}"><i class="icon-arrow-right"></i> Manage Client</a></li>
                        <li><a class="waves-effect waves-dark" href="{{route('manageclient.create')}}"><i class="icon-arrow-right"></i> Add Client</a></li>
                    </ul>
                </li>

                <li class="treeview"><a class="waves-effect waves-dark" href="#!"><i class="fa fa-building-o" aria-hidden="true"></i><span>Campaign Organizations</span><i class="icon-arrow-down"></i></a>
                    <ul class="treeview-menu">
                        <li><a class="waves-effect waves-dark" href="{{route('managecampaignorgs.list')}}"><i class="icon-arrow-right"></i> Manage Organizations</a></li>
                        <li><a class="waves-effect waves-dark" href="{{route('managecampaignorgs.create')}}"><i class="icon-arrow-right"></i> Add Organizations</a></li>
                    </ul>
                </li>

                <li class="treeview"><a class="waves-effect waves-dark" href="#!"><i class="fa fa-desktop" aria-hidden="true"></i><span>Manage Quick Software</span><i class="icon-arrow-down"></i></a>
                    <ul class="treeview-menu">
                        <li><a class="waves-effect waves-dark" href="{{route('managequicksoftware.list')}}"><i class="icon-arrow-right"></i> Manage Quick Software</a></li>
                        <li><a class="waves-effect waves-dark" href="{{route('managequicksoftware.create')}}"><i class="icon-arrow-right"></i> Add Software</a></li>
                    </ul>
                </li>

                <li class="treeview"><a class="waves-effect waves-dark" href="#!"><i class="fa fa-server" aria-hidden="true"></i><span>Manage Service</span><i class="icon-arrow-down"></i></a>
                    <ul class="treeview-menu">
                        <li><a class="waves-effect waves-dark" href="{{route('manageservices.list')}}"><i class="icon-arrow-right"></i> Manage Service</a></li>
                        <li><a class="waves-effect waves-dark" href="{{route('manageservices.create')}}"><i class="icon-arrow-right"></i> Add Service</a></li>
                    </ul>
                </li>

                <li class="treeview"><a class="waves-effect waves-dark" href="#!"><i class="fa fa-question-circle" aria-hidden="true"></i><span>Manage FAQ</span><i class="icon-arrow-down"></i></a>
                    <ul class="treeview-menu">
                        <li><a class="waves-effect waves-dark" href="{{route('managefaqs.list')}}"><i class="icon-arrow-right"></i> Manage FAQ</a></li>
                        <li><a class="waves-effect waves-dark" href="{{route('managefaqs.create')}}"><i class="icon-arrow-right"></i> Add FAQ</a></li>
                    </ul>  
                </li>

                <li class="treeview"><a class="waves-effect waves-dark" href="#!"><i class="fa fa-address-book" aria-hidden="true"></i><span>Manage Contacts Data</span><i class="icon-arrow-down"></i></a>
                    <ul class="treeview-menu">
                        <li><a class="waves-effect waves-dark" href="accordion.html"><i class="icon-arrow-right"></i> Manage Contacts</a></li>
                        <li><a class="waves-effect waves-dark" href="button.html"><i class="icon-arrow-right"></i>Import Contacts Data</a></li>
                    </ul>
                </li>

                <li class="treeview"><a class="waves-effect waves-dark" href="#!"><i class="icon-briefcase"></i><span>Manage Election Result</span><i class="icon-arrow-down"></i></a>
                    <ul class="treeview-menu">
                        <li><a class="waves-effect waves-dark" href="{{route('manageelectionresult.list')}}"><i class="icon-arrow-right"></i> Manage Election</a></li>
                        <li><a class="waves-effect waves-dark" href="button.html"><i class="icon-arrow-right"></i>Election Year</a></li>
                        <li><a class="waves-effect waves-dark" href="{{route('manageelection.create')}}"><i class="icon-arrow-right"></i> Add Election</a></li>
                        <li><a class="waves-effect waves-dark" href="{{route('votesimport.addImport')}}"><i class="icon-arrow-right"></i> Contact Import</a></li>
                    </ul>
                </li>

                <li class="treeview"><a class="waves-effect waves-dark" href="#!"><i class="fa fa-file" aria-hidden="true"></i><span> Report</span><i class="icon-arrow-down"></i></a>
                    <ul class="treeview-menu">
                        <li><a class="waves-effect waves-dark" href="{{route('manageteam.list')}}"><i class="icon-arrow-right"></i> Manage Team</a></li>
                        <li><a class="waves-effect waves-dark" href="{{route('managesurvey.list')}}"><i class="icon-arrow-right"></i> Survey List</a></li>
                        <li><a class="waves-effect waves-dark" href="{{route('reportissue.list')}}"><i class="icon-arrow-right"></i> Issue</a></li>
                    </ul>
                </li>

                <li class="treeview"><a class="waves-effect waves-dark" href="{{route('manageelectionvoters.list')}}"><i class="fa fa-calculator" aria-hidden="true"></i><span> Manage Election Count</span></a>
                </li>
            </ul>
         </section>
      </aside>
      <!-- Sidebar chat start -->