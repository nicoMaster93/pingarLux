const service = class {
    
    constructor(url) {
        this.myHeaders = new Headers();
        this.myHeaders.append("Content-Type", "application/json");
        this.url = url ?? "http://localhost/proyectsInHouse/JP/backend/?";
        
    }
    config(method,data,json=true){
        var requestOptions = {
            method: method,
            headers: this.myHeaders,
            body: (json ? JSON.stringify(data) : data ),
            withCredentials: true,  
            crossorigin: true,  
        };
        if(method === 'GET'){
            delete requestOptions.body;
        }
        return requestOptions;
    }
    post = async (endPoint,data,json=true)=>{
        const url = this.url + endPoint;
        const response = await fetch(url, this.config('POST',data,json));
        return response.json();
    }
    put = async (endPoint,data,json=true)=>{
        const url = this.url + endPoint;
        const response = await fetch(url, this.config('PUT',data,json));
        return response.json();
    }
    delete = async (endPoint,data,json=true)=>{
        const url = this.url + endPoint;
        const response = await fetch(url, this.config('DELETE',data,json));
        return response.json();
    }
    get = async (endPoint,data)=>{
        const url = this.url + endPoint + (data ? `&` + (new URLSearchParams(data)) : '' );
        const response = await fetch(url, this.config('GET',""));
        return response.json();
    }
    html = async (endPoint)=>{
        const url = this.url + endPoint;
        const response = await fetch(url, this.config('GET',""));
        return response.text();
    }
}