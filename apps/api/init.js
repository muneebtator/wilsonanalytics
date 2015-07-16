function wilson(){
    this.appname = null, this.storekey = null, this.modules = [];

  this.initapp =  function(tname, skey, mod){
      this.appname = tname;
      this.storekey = skey;
        if(mod instanceof Array === false){
            console.error("Modules param should be an Array.");
            return false;
        }
    this.modules = mod;
  }

this.WQL = function(query, count, callback){
   (typeof(count) == "undefined") ? count = null : true;
    if(this.modules.indexOf('WQL') < 0){
        console.error("Use the WQL module.");
        return false;
      }
      $.ajax({
        url: 'apps/api/post/wql.php',
        type: 'POST',
        dataType: 'JSON',
        data: {wquery: query, count: count},
      }).done(function(data){
          if(data.error != null){
            console.error(data.error);
          }else if(data.warning != null){
            console.warn(data.warning);
          }else{
              return callback(data);
          }
      });
  }

 

  this.datastore = function(action, name, data, cat, website, callback){
      if(this.modules.indexOf('datastore') < 0){
        console.error("Use the datastore module.");
        return false;
      }
   (typeof(cat) == "undefined") ? cat = null : true;
   (typeof(name) == "undefined") ? name = null : true;
   (typeof(data) == "undefined") ? data = null : true;
    (typeof(website) == "undefined") ? website = null : true;
    if(this.storekey == null){
        console.error("Store key is required for data storing. Please check your store key.");
        return false;
    }
      $.ajax({
            url: 'apps/api/post/storemanager.php',
            type: 'POST',
            dataType: 'JSON',
            data: {action: action, name: name, cat: cat, storekey: this.storekey, data: data, website: website, from: null
            },
          }).done(function(data){
              if(data.error != null){
                  console.error(data.error);
              }else if(data.warning != null){
                  console.warn(data.warning);
              }else{
                if(typeof(callback) == "function"){
                  return callback(data);
                }
              }
      });
}

  this.gettraffic = function(websiteid, order, type, callback){
      (typeof(type) == "undefined") ? type = null : true;
     $.ajax({
            url: 'apps/api/post/gettraffic.php',
            type: 'POST',
            dataType: 'JSON',
            data: {website: websiteid, orderby: order, type: type},
          }).done(function(data){
              if(data.error != null){
                  console.error(data.error);
              }else if(data.warning != null){
                  console.warn(data.warning);
              }else{
                if(typeof(callback) == "function"){
                  return callback(data);
                }
              }
      });

  }
}