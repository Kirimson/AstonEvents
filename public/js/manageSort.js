function sort(type, url) {
    //if has orderBy
    if (url.includes("orderBy")) {

        let urlSplit = url.split("?");
        let params = urlSplit[1].split("&");

        //Variable for new url parameters
        let newGet = '';

        //check if new order atr is same as old
        if (url.includes("orderBy=" + type)) {
            //if same atr, keep value, and invert order

            //If there is no order param, add its default value in
            if(url.includes("order=") === false){
                params.push("order=ascending");
            }

            //Build new query
            params.forEach(function (element) {
                if (element.includes("order=")) {
                    if (element.includes("ascending")) {
                        newGet += "order=descending&";
                    } else {
                        newGet += "order=ascending&";
                    }
                } else newGet += element + "&";
            });
        } else {
            //if new atr, replace the value with new value, and set order to ascending

            //Build new query
            params.forEach(function (element) {
                if (element.includes("orderBy=")) {
                    newGet += "orderBy=" + type + "&";
                } else if (element.includes("order")) {
                    newGet += "order=ascending&";
                } else newGet += element + "&";
            });
        }
        //Remove last & in params, too much work to check if on last if this always works
        newGet = newGet.substr(0, newGet.length - 1);

        let baseurl = window.location.origin + window.location.pathname;
        window.location.replace(baseurl + "?" + newGet);

    } else {
        //If has get params already. but no orderBy, set order by
        if (url.includes("?")) {
            window.location.replace(url + "&order=ascending" + "&orderBy=" + type);
        } else {
            window.location.replace(url + "?order=ascending" + "&orderBy=" + type);
        }
    }
}