import React from 'react';
import PropTypes from 'prop-types';


class SelectWithAdd extends React.Component{
    constructor(props){
        super(props);

        this.state = {
            options:[],
            fields: this.props.fields,
            selectedOption: '',
            isLoading: false
        };
    }
    
    componentWillReceiveProps(){
        //did mount doesnt run when changing days, so selects selected item must be reset whenever
        //new props are coming
        if (!this.props.value && this.selectElement) {
            this.setState({selectedOption: this.props.isObject ? 0 : this.selectElement.options[0] ? this.selectElement.options[0].value : ''});
            this.selectElement.selectedIndex = 0;
        }
    }
    
    componentDidMount(props){
        this.setState({isLoading:true});
        
        fetch(this.props.url)
        .then(results => results.json())
        .then(data => {
            
            if (this.props.isObject){
                let options_from_object = [];
                
                if (this.props.canBeNull) options_from_object.push({[this.props.listField]: ''});
                
                for (var prop in data) {
                    let temp_object = {};
                    temp_object[prop] = data[prop];
                    options_from_object.push(temp_object);
                }
                
                this.setState(
                    {
                        options:options_from_object,
                        selectedOption: 0
                    });
                    
            }
            else{

                if(this.props.translateFields){
                    let translated_data = [];
                    
                    translated_data = data.map((e,i) => {
                       let temp_object = {};
                       for (let prop in e){
                           for (let a = 0; a < this.props.translateFields.length; a++){
                               if (prop == this.props.translateFields[a][0]){
                                   temp_object[this.props.translateFields[a][1]] = e[prop];
                               }
                           }
                           temp_object[prop] = e[prop];
                       }
                       
                       return temp_object;
                    });
                    //console.log(translated_data);
                    data = translated_data;
                }
                
                if (this.props.filter){
                    
                    
                    let filtered_options = data.filter((option) => option[this.props.filter[0]] == this.props.filter[1]);
                    
                    if (this.props.canBeNull) filtered_options.unshift({[this.props.listField]: '',[this.props.valueField]: ''});
                    
                    
                    this.setState(
                    {
                        options:filtered_options,
                        selectedOption: filtered_options[0][this.props.valueField]
                    }
                    );
                }
                else{
                    if (this.props.canBeNull) data.unshift({[this.props.listField]: '',[this.props.valueField]: ''});
                    
                    this.setState(
                    {
                        options:data,
                        selectedOption: data[0][this.props.valueField]
                    }
                    );
                }
            }
            
            this.setState({isLoading:false});
            this.triggerOnSelect();
            
        })
        .catch(e => {
            console.log("Error: ", e.message);
        });
    }
    
    search(key, mySet){
        for (var i=0; i < mySet.length; i++) {
            if (mySet[i][this.props.valueField] === key) {
                return mySet[i];
            }
        }
        return null;
    }

    
    updateSelect(e){
        if (this.props.isObject){
            this.setState({
                selectedOption: e.target.selectedIndex
            },this.triggerOnSelect);
        }
        else{
            this.setState({
                selectedOption: e.target.value
            },this.triggerOnSelect);
        }
    }
    
    triggerOnSelect(){
        if (this.props.onSelect) this.props.onSelect(this.getSelectedOption());
    }
    
    getSelectedOption(){
        
        if (this.props.canBeNull && !this.state.selectedOption){
            return null;
        }
        
        if (this.props.returnValueFieldValue){
            return this.state.selectedOption ? this.state.selectedOption : null;
        }
        else{
            let returnObject = this.props.isObject ? this.state.options[this.state.selectedOption] : this.search(this.state.selectedOption, this.state.options);
        
            if (this.props.returnFields){
                let temp_object_to_return = {};
                
                if (this.props.isObject){
                    
                    let returnObjectInfo = returnObject[Object.keys(returnObject)[0]];
                    
                    for (let i = 0; i < this.props.returnFields.length; i++){
                        temp_object_to_return[this.props.returnFields[i]] = returnObjectInfo.info[this.props.returnFields[i]] ? returnObjectInfo.info[this.props.returnFields[i]] : null;
                    }
                    
                    if(this.props.translateFields){
                        let translated_data = {
                            info: {}
                        };
                        
                        for (let prop in returnObjectInfo.info){
                            let translated = false;
                            for (let a = 0; a < this.props.translateFields.length; a++){
                               if (prop == this.props.translateFields[a][0]){
                                   translated_data.info[this.props.translateFields[a][1]] = returnObjectInfo.info[prop];
                                   translated = true;
                                   break;
                               }
                            }
                            if(!translated){
                                translated_data.info[prop] = returnObjectInfo.info[prop];
                            }
                        }
                        
                        returnObjectInfo = translated_data;
                    }
                    
                    for (let i = 0; i < this.props.returnFields.length; i++){
                        temp_object_to_return[this.props.returnFields[i]] = returnObjectInfo.info[this.props.returnFields[i]] ? returnObjectInfo.info[this.props.returnFields[i]] : null;
                    }
                    
                }
                else if (returnObject) {
                    
                    for (let i = 0; i < this.props.returnFields.length; i++){
                        temp_object_to_return[this.props.returnFields[i]] = returnObject[this.props.returnFields[i]] ? returnObject[this.props.returnFields[i]] : null;
                    }
                }
                
                return temp_object_to_return;
            } else
                return returnObject ? returnObject : null;
        }
        
    }
    
    
    render(){
        
        if(this.state.isLoading){
            return (<img src="../src/img/loader.gif" width="25" />);
        }
        
        const selectOptions = this.state.options.map( (e,i) => {
            
            // this will build an object that will consist of the attributes for the options
            let attributes = {};
            
            if (this.props.isObject){
                let current_object = e[Object.keys(e)[0]];
                
                for (let field in this.state.fields) {
                    attributes[this.state.fields[field]] = current_object.info[this.state.fields[field]];
                }
                return <option key={i} {...attributes} value={Object.keys(e)[0]}>{current_object.info[this.props.listField]} - {Object.keys(e)[0]}</option>;
            }
            else{
                for (let field in this.state.fields) {
                    attributes[this.state.fields[field]] = e[this.state.fields[field]];
                }
                return <option key={i} {...attributes} value={e[this.props.valueField]}>{e[this.props.listField]}</option>;
            }
            
        });
        
        return(
            
            <div className="input-group">
                <select 
                    ref={(select) => { this.selectElement = select; }}
                    className="form-control" 
                    onChange={(e) => this.updateSelect(e)}
                    value={this.props.value ? this.props.value : this.selectElement && this.selectElement.options[this.state.selectedOption] ? this.selectElement.options[this.state.selectedOption].value : this.state.selectedOption}
                    >
                    {selectOptions}
                </select>
                {(this.props.onAdd) ? (
                    <div className="input-group-append">
                        <button className="btn btn-outline-secondary" type="button" onClick={ e => this.props.onAdd(this.getSelectedOption())}>ADD</button>
                    </div>
                ) : ''}
            </div>
            
        );
    }
}

SelectWithAdd.propTypes = {
   url: PropTypes.string,
   fields: PropTypes.array,
   returnFields: PropTypes.array,
   translateFields: PropTypes.array,
   filter: PropTypes.array,
   valueField: PropTypes.string,
   value: PropTypes.string,
   listField: PropTypes.string,
   isObject: PropTypes.bool,
   canBeNull: PropTypes.bool,
   returnValueFieldValue: PropTypes.bool,
   onAdd: PropTypes.func,
   onSelect: PropTypes.func
};

export default SelectWithAdd;