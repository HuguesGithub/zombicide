<section id="page-competences">
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

  <div class="popover fade bs-popover-top" role="tooltip" id="popoverDescription" style="will-change: transform;" x-placement="top">
    <div class="arrow" style="left: 70px;"></div>
    <h3 class="popover-header">Rechercher dans la Description</h3>
    <div class="popover-body">
      <input type="text" id="description" name="description" class="custom-input custom-input-sm filters" value="%14$s"/>
    </div>
  </div>

  <table class="table table-striped table-sm" role="grid">
    <thead>
      <tr role="row">
        <th class="sorting%5$s ajaxAction" data-colsort="name" data-colorder="%5$s" data-ajaxaction="sort">Nom</th>
        <th>Niveau</th>
        <th class="filter ajaxAction" data-filter="description" data-ajaxaction="filter" >Description
          <i class="fas fa-filter float-right" style="margin-top:3px;"></i></th>
      </tr>
    </thead>
    <tfoot>
      <tr>
        <th>Nom</th>
        <th>Niveau</th>
        <th>Description</th>
      </tr>
    </tfoot>
    <tbody>%6$s</tbody>
  </table>
  <nav>
    <ul class="pagination pagination-sm float-left">
      <li class="page-item disabled"><a class="page-link" href="#" tabindex="-1">Affichés %7$s à %8$s sur %9$s résultats</a></li>
    </ul>
    <ul class="pagination justify-content-end pagination-sm">
      <li class="page-item%11$s"><a class="page-link ajaxAction" href="#" data-paged="1" data-ajaxaction="paged">&laquo;</a></li>
      %10$s
      <li class="page-item%12$s"><a class="page-link ajaxAction" href="#" data-paged="%13$s" data-ajaxaction="paged">&raquo;</a></li>
    </ul>
  </nav>
</section>
