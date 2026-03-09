export class ApiRequest {
    static async request(endpoint, method = 'GET', body = null) {
        const options = {
            method: method,
            headers: { 'Content-Type': 'application/json' }
        };
        if (body) options.body = JSON.stringify(body);
        try {
            const response = await fetch(`/services/${endpoint}`, options);
            const data = await response.json();
            
            if (!response.ok) 
                throw new Error(data.message || "Errore di rete");
            return data;
            
        } catch (error) {
            console.error("API Error:", error);
            throw error; 
        }
    }
}