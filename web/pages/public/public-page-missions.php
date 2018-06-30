<section id="page-missions">
  <div class="dropdown">
    <button class="btn btn-sm" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      Afficher par page :
    </button>
    <select id="displayedRows" class="custom-select custom-select-sm" style="width: inherit;">
      <option value="10" %1$s class="ajaxAction" data-ajaxaction="display">10 résultats</option>
      <option value="25" %2$s class="ajaxAction" data-ajaxaction="display">25 résultats</option>
      <option value="50" %3$s class="ajaxAction" data-ajaxaction="display">50 résultats</option>
    </select>
  </div>
  
  <table class="table table-striped table-sm" role="grid">
    <thead>
      <tr role="row">
        <th class="sorting%4$s ajaxAction" data-colsort="code" data-colorder="%4$s" data-ajaxaction="sort">Code</th>
        <th class="sorting%5$s ajaxAction" data-colsort="title" data-colorder="%5$s" data-ajaxaction="sort">Nom</th>
        <th class="filter ajaxAction" data-filter="level" data-ajaxaction="filter">Difficulté <i class="fas fa-filter float-right" style="margin-top:3px;"></i></th>
        <th class="filter ajaxAction" data-filter="duration" data-ajaxaction="filter">Durée <i class="fas fa-filter float-right" style="margin-top:3px;"></i></th>
        <th class="filter ajaxAction" data-filter="player" data-ajaxaction="filter">Joueurs <i class="fas fa-filter float-right" style="margin-top:3px;"></i></th>
        <th class="filter ajaxAction" data-filter="expansion" data-ajaxaction="filter">Extensions <i class="fas fa-filter float-right" style="margin-top:3px;"></i></th>
        <th class="filter ajaxAction" data-filter="origine" data-ajaxaction="filter">Origine <i class="fas fa-filter float-right" style="margin-top:3px;"></i></th>
        <!--
        <th>Dalles</th>
        -->
      </tr>
      <tr id="rowFilterMission" class="%18$s"><th>&nbsp;</th><th>&nbsp;</th><th>%6$s</th><th>%16$s</th><th>%7$s</th><th>%19$s</th><th>%17$s</th></tr>
    </thead>
    <tfoot>
      <tr>
        <th>Code</th>
        <th>Nom</th>
        <th>Difficulté</th>
        <th>Durée</th>
        <th>Joueurs</th>
        <th>Extensions</th>
        <th>Origine</th>
        <!--
        <th>Dalles</th>
        -->
      </tr>
    </tfoot>
    <tbody>%8$s</tbody>
  </table>
  <nav>
    <ul class="pagination pagination-sm float-left">
      <li class="page-item disabled"><a class="page-link" href="#" tabindex="-1">Affichés %9$s à %10$s sur %11$s résultats</a></li>
    </ul>
    <ul class="pagination justify-content-end pagination-sm">
      <li class="page-item%13$s"><a class="page-link ajaxAction" href="#" data-paged="1" data-ajaxaction="paged">&laquo;</a></li>
      %12$s
      <li class="page-item%14$s"><a class="page-link ajaxAction" href="#" data-paged="%15$s" data-ajaxaction="paged">&raquo;</a></li>
    </ul>
  </nav>
</section>