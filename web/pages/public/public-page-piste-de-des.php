<section id="page-piste-de-des">
  <div>%1$s</div>
  <form action="#" method="post" class="row">
    <div class="col-12 col-md-6 col-xl-3">
      <div class="input-group mb-3">
        <div class="input-group-prepend">
          <span class="input-group-text" id="label-nbDeDes">Nombre de dés</span>
        </div>
        <input id="nbDeDes" name="nbDeDes" class="form-control" placeholder="Saisir un nombre" type="text">
      </div>
    </div>
    <div class="col-12 col-md-6 col-xl-3">
      <div class="input-group mb-3">
        <div class="input-group-prepend">
          <label class="input-group-text" for="seuilReussite">Seuil de réussite</label>
        </div>
        <select class="custom-select" id="seuilReussite" name="seuilReussite">
          <option value="6">6</option>
          <option value="5">5</option>
          <option value="4">4</option>
          <option value="3">3</option>
          <option value="2">2</option>
          <option value="1">1</option>
        </select>
      </div>
    </div>
    <div class="col-12 col-md-6 col-xl-3">
      <div class="input-group mb-3">
        <div class="input-group-prepend">
          <label class="input-group-text" for="surUn6">Sur un 6 : +1 dé</label>
        </div>
        <select class="custom-select" id="surUn6" name="surUn6">
          <option value="0">Non</option>
          <option value="1">Oui</option>
        </select>
      </div>
    </div>
    <div class="col-12 col-md-6 col-xl-3">
      <div class="input-group mb-3">
        <div class="input-group-prepend">
          <label class="input-group-text" for="plusUnAuDe">+1 au résultat du dé</label>
        </div>
        <select class="custom-select" id="plusUnAuDe" name="plusUnAuDe">
          <option value="0">Non</option>
          <option value="1">Oui</option>
        </select>
      </div>
    </div>
    <div class="col-12">
      <button class="btn btn-primary" type="submit">Lancer</button>
    </div>
  </form>
</section>
