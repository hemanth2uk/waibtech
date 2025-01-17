  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
        <i class="fa fa-circle text-success"></i>
        </div>
        <div class="pull-left info">
          <p> {{ Auth::user()->name }}</p>
          <a href="{{route('dashboard')}}"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>
      <!-- search form -->
      <form action="#" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Search...">
          <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat">
                  <i class="fa fa-search"></i>
                </button>
              </span>
        </div>
      </form>
      <!-- /.search form -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">MAIN NAVIGATION</li>
    
        
        <li>
          <a href="{{route('users')}}">
          <i class="fa fa-user-circle-o"></i> <span>Users</span>
        
          </a>
        </li>
        <li>
          <a href="{{route('quiz')}}">
          <i class="fa fa-user-circle-o"></i> <span>Quiz</span>
        
          </a>
        </li>
        
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>