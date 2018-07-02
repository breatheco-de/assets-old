import Flux from '@4geeksacademy/react-flux-dash';

export const loadReplits = (cohort)=>{
    let endpoint = process.env.hostAssets+'/apis/replit/cohort/'+cohort;
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
    let endpoint = process.env.hostAssets+'/apis/replit/template/'+profile_slug;
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