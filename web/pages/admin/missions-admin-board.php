<div class="wrap">
  <h1 class="wp-heading-inline">Missions</h1>
  <a href="%3$s" class="page-title-action">Ajouter</a>
  <hr class="wp-header-end">
  
  <h2 class="screen-reader-text">Filtrer la liste des missions</h2>
  <ul class="subsubsub">%4$s</ul>
  
  <form action="#" method="post" id="post-filters">
    <div class="tablenav top">
      <div class="alignleft actions bulkactions">
      <!--
        <label for="bulk-action-selector-top" class="screen-reader-text">Sélectionnez l’action groupée</label>
        <select name="action" id="bulk-action-selector-top">
          <option value="-1">Actions groupées</option>
          <option value="edit" class="hide-if-no-js">Modifier</option>
          <option value="trash">Déplacer dans la corbeille</option>
          <option value="duplicate_post_clone">Dupliquer</option>
        </select>
        <input id="doaction" class="button action" value="Appliquer" type="submit">
      -->
      </div>
      <div class="alignleft actions" style="display: inline; float: none;">%2$s<input name="filter_action" id="post-query-submit" class="button" value="Filtrer" type="submit"></div>
      <!--
      <h2 class="screen-reader-text">Navigation de la liste des articles</h2>
      -->
      <div class="tablenav-pages" style="height: 34px;">%5$s</div>
      <br class="clear">
    </div>  
    <table class="table table-striped table-bordered table-hover table-sm">
      <thead>
        <tr>
          <td id="cb" class="manage-column column-cb check-column"><input id="cb-select-all-1" type="checkbox"></td>
          <th scope="col" id="code" class="manage-column sortable %6$s"><a href="%7$s" style="padding: 0;"><span>Code</span><span class="sorting-indicator"></span></a></th>
          <th scope="col" id="title" class="manage-column column-primary sortable %8$s"><a href="%9$s" style="padding: 0;"><span>Titre</span><span class="sorting-indicator"></span></a></th>
          <th scope="col" id="levelId" class="manage-column">Difficulté</th>
          <th scope="col" id="durationId" class="manage-column">Durée</th>
          <th scope="col" id="playerId" class="manage-column">Joueurs</th>
          <th scope="col" id="origineId" class="manage-column">Origine</th>
          <th scope="col" id="tiles" class="manage-column">Dalles</th>
          <th scope="col" id="rules" class="manage-column">Règles</th>
          <th scope="col" id="objectives" class="manage-column">Objectifs</th>
          <th scope="col" id="expansions" class="manage-column">Extensions</th>
        </tr>
      </thead>
      <tbody id="the-list">%1$s</tbody>
      <tfoot>
        <tr>
          <td class="manage-column column-cb check-column"><input id="cb-select-all-2" type="checkbox"></td>
          <th scope="col" class="manage-column sortable %6$s"><a href="%7$s" style="padding: 0;"><span>Code</span><span class="sorting-indicator"></span></a></th>
          <th scope="col" class="manage-column column-primary sortable %8$s"><a href="%9$s" style="padding: 0;"><span>Titre</span><span class="sorting-indicator"></span></a></th>
          <th scope="col" class="manage-column">Difficulté</th>
          <th scope="col" class="manage-column">Durée</th>
          <th scope="col" class="manage-column">Joueurs</th>
          <th scope="col" class="manage-column">Origine</th>
          <th scope="col" class="manage-column">Dalles</th>
          <th scope="col" class="manage-column">Règles</th>
          <th scope="col" class="manage-column">Objectifs</th>
          <th scope="col" class="manage-column">Extensions</th>
        </tr>
      </tfoot>
    </table>
  </form>
</div>