import Flux from '@4geeksacademy/react-flux-dash';

let _bcToken = null;
let _assetsToken = null;
export const loadTokens = () => {
    var url = new URL(window.location.href);
    _bcToken = url.searchParams.get("bc_token");
    _assetsToken = url.searchParams.get("assets_token");
    if(!_bcToken && !_assetsToken) console.warn("No bc_token or assets_token have been detected on the URL");
};
export const tokens = () => {
    return {
        bcToken: _bcToken,
        assetsToken: _assetsToken
    }
}

export const loadReplits = (cohort)=>{
    let endpoint = process.env.ASSETS_HOST+'/apis/replit/cohort/'+cohort;
    fetch(endpoint)
        .then((resp) => {
            if(resp.status != 200){
                alert('The cohort has no previous replits');
            }
            else return resp.json();
        })
        .then((data) => {
            if(typeof data != 'undefined' && data) Flux.dispatchEvent('replits', data);

        })
        .catch((error) => {
            console.log('error', error);
        });
}

export const loadTemplates = (profile_slug)=>{
    let endpoint = process.env.ASSETS_HOST+'/apis/replit/template/'+profile_slug;
    fetch(endpoint)
        .then((response) => {
            return response.json();
        })
        .then((data) => {
            Flux.dispatchEvent('templates', data);
        })
        .catch((error) => {
            console.log('error', error);
        });
}

class _store extends Flux.DashStore{
    constructor(){
        super();
        let templatesAdded = false;
        this.addEvent('replits', (replits)=>{
            if(!templatesAdded) return replits;
            let templates = this.getState('templates');
            if(!Array.isArray(templates)) return templates;

            let newReplits = (replits) ? replits : {};
            templates.forEach((t)=>{
                if(typeof newReplits[t.slug] == 'undefined') newReplits[t.slug] = t.base || '';
            });
            return replits;
        });
        this.addEvent('templates', (templates)=>{
            templatesAdded = true;
            if(!Array.isArray(templates)) return templates;

            let oldReplits = this.getState('replits');
            let newReplits = (oldReplits) ? oldReplits : {};
            templates.forEach((t)=>{
                if(typeof newReplits[t.slug] == 'undefined') newReplits[t.slug] = t.base || '';
            });
            this.events.forEach((event)=>{
                if(event.name == 'replits') event.value = newReplits;
            });
            return templates;
        });
    }
}

export let store = new _store();