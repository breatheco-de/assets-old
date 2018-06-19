import React from 'react';

//include images into your bundle
import rigoImage from '../../img/rigo-baby.jpg';
import SelectWithAdd from './SelectWithAdd';
import InputWithAdd from './InputWithAdd';
import Modal from './Modal';

/*
    KNOWN ISSUES:
        - If you move all the days from a week, that week becomes unreachable
*/

export class CreateCard extends React.Component{
    
    constructor(){
        super();
        
        this.state = { 
          days: [],
          syllabus: {},
          syllabus_succeeded: false,
          
          s_label: '',
          s_description: '',
          s_instructions: '',
          s_project: {},
          s_projects: [],
          s_homework: '',
          's_key-concepts': [],
          s_lessons:[],
          s_quizzes:[],
          s_replits:[],
          s_assignments:[],
          s_technologies:[],
          
          has_changed: false,
          
          move_to_week: 0,
          move_to_day_in_week: 0,
          insert_new_week: false,
          insert_after_selected: false,
          is_empty: false,
          
          delete_modal_show: false
        };
    }
    
    
    //Load a day
    loadDay(e, week, day, day_in_syllabus){
        if (e) e.preventDefault();
        
        console.log("Week: ",week, " - Day: ", day, " - Ordinal: ", day_in_syllabus);
        
        let week_to_load = Object.assign({}, this.state.syllabus.weeks[week]);
        let day_to_load = Object.assign({}, this.state.syllabus.weeks[week].days[day]);
        
        this.setState({
            s_week_index: week,
            s_day_index: day,
            s_day_in_syllabus: day_in_syllabus,
            s_week_label: week_to_load.label ? week_to_load.label : '',
            s_week_topic: week_to_load.topic ? week_to_load.topic : '',
            s_week_summary: week_to_load.summary ? week_to_load.summary : '',
            s_week_warning: week_to_load.warning ? week_to_load.warning : '',
            s_label: day_to_load.label ? day_to_load.label : '',
            s_description: day_to_load.description ? day_to_load.description : '',
            s_instructions: day_to_load.instructions ? day_to_load.instructions : '',
            s_project: day_to_load.project ? day_to_load.project : {},
            s_homework: day_to_load.homework ? day_to_load.homework : '',
            's_key-concepts': day_to_load['key-concepts'] ? day_to_load['key-concepts'] : [],
            s_lessons: day_to_load.lessons ? day_to_load.lessons : [],
            s_quizzes: day_to_load.quizzes ? day_to_load.quizzes : [],
            s_replits: day_to_load.replits ? day_to_load.replits : [],
            s_assignments: day_to_load.assignments ? day_to_load.assignments : [],
            s_technologies: day_to_load.technologies ? day_to_load.technologies : [],
            
            // move_to_week: week,
            // move_to_day_in_week: day
            insert_new_week: false
        },
            () => this.resetChanges()
        );
    }
    
    //If something has been changed, this would save those to the current day
    saveChanges(e){
        e.preventDefault();
        
        let current_week_index = this.state.s_week_index;
        let current_day_index = this.state.s_day_index;
        let current_syllabus = Object.assign({}, this.state.syllabus);
        let current_week = current_syllabus.weeks[current_week_index] ? current_syllabus.weeks[current_week_index] : {};
        let current_day = current_syllabus.weeks[current_week_index].days[current_day_index] ? current_syllabus.weeks[current_week_index].days[current_day_index] : {};
        
        current_week.label = this.state.s_week_label;
        current_week.topic = this.state.s_week_topic;
        current_week.summary = this.state.s_week_summary;
        current_week.warning = this.state.s_week_warning;
        current_day.label = this.state.s_label;
        current_day.description = this.state.s_description;
        current_day.instructions = this.state.s_instructions;
        current_day.project = this.state.s_project === null ? {} : this.state.s_project;
        current_day.homework = this.state.s_homework;
        current_day['key-concepts'] = this.state['s_key-concepts'];
        current_day.lessons = this.state.s_lessons;
        current_day.quizzes = this.state.s_quizzes;
        current_day.replits = this.state.s_replits;
        current_day.assignments = this.state.s_assignments;
        current_day.technologies = this.state.s_technologies;
        
        for (let prop in current_day){
            if (current_day[prop] === '' || (Object.keys(current_day[prop]).length === 0 && current_day[prop].constructor === Object) || current_day[prop].length === 0)
                delete current_day[prop];
        }
        
        if (current_week.warning === '')
                delete current_week['warning'];
        
        if (!current_syllabus.weeks[current_week_index].days[current_day_index]) current_syllabus.weeks[current_week_index].days[current_day_index] = current_day;
        
        this.setState({
            syllabus: current_syllabus
        }, 
            () => this.resetChanges()
        );
    }
    
    //Download JSON and create a demo card with the JSON code
    realSubmit(){
        let html_code = '<div class="card border-info mb-3"><div class="card-body">';
        
        
        this.createDownload(JSON.stringify(this.state.syllabus));
        
        html_code += JSON.stringify(this.state.syllabus);
        html_code += '</div></div>';
        
        document.querySelector('.json-output').innerHTML = html_code;
    }
    
    //Override the SUBMIT even in the form
    handleFormSubmit(e) {
        e.preventDefault();
        
        return false;
    }
    
    //Signal that some data has changed, and activate the SAVE button
    dataHasChanged(){
        this.setState({
            has_changed: true
        });
    }
    
    //Signal that all data has been saved, and disable the SAVE button
    resetChanges(){
        this.setState({
            has_changed: false
        });
    }
    
    //Delete option from array, receives the target array, and the index to be deleted
    deleteFromArray(target,index){
        this.setState({
           [target]:  this.state[target].filter((e,i) => i != index)
        });
        
        //notify that a change has been made
        this.dataHasChanged();
    }
    
    //Add option to array, received the target array, and the data to be added
    addToArray(target,thingToAdd){
        this.setState({
            [target]: this.state[target].concat([thingToAdd])
        });
        
        //notify that a change has been made
        this.dataHasChanged();
    }
    
    //Set value of a property in the state, receives the target property and the value to be set
    setValue(target,thingToSet){
        this.setState({
            [target]: thingToSet
        });
        
        //notify that a change has been made
        this.dataHasChanged();
    }
    
    //Creates the downloadable link so a download can be triggered
    createDownload(text){
        var element = document.createElement('a');
        element.setAttribute('href', 'data:text/json;charset=utf-8,' + encodeURIComponent(text));
        element.setAttribute('download', 'syllabus.json');
        element.style.display = 'none';
        document.body.appendChild(element);
        
        element.click();
        document.body.removeChild(element);
    }
    
    //Loads the syllabus JSON
    pullSyllabus(){
        const url = document.querySelector('#syllabus_url').value;
        fetch(url)
        .then(results => results.json())
        .then(data => {
            this.setState({
                syllabus: data,
                syllabus_succeeded: true
            });
        });
    }
    
    //Change syllabus related properties
    handleSyllabus(property, event){
        let temp_syllabus = Object.assign({}, this.state.syllabus);
        
        temp_syllabus[property] = event.target.value;
        
        this.setState({
            syllabus: temp_syllabus
        });
    }
    
    //Shift by a day the selected one
    selectNextDay(){
        let day_to_move_to = 0;
        let week_to_move_to = this.state.s_week_index;
        let max_day_index_this_week = this.state.syllabus.weeks[this.state.s_week_index].days.length - 1;
        let max_week_index_this_syllabus = this.state.syllabus.weeks.length - 1;
        
        if (this.state.s_day_index != max_day_index_this_week)
            day_to_move_to = this.state.s_day_index + 1;
        else if (this.state.s_week_index != max_week_index_this_syllabus){
            week_to_move_to = this.state.s_week_index + 1;
            day_to_move_to = 0;
        }
        
        this.loadDay(null,week_to_move_to,day_to_move_to, this.state.s_day_in_syllabus + 1);
    }
    
    //Shift back a day the selected one
    selectPreviousDay(){
        let day_to_move_to = 0;
        let week_to_move_to = this.state.s_week_index;
        let min_day_index_this_week = 0;
        let min_week_index_this_syllabus = 0;
        
        if (this.state.s_day_index != min_day_index_this_week)
            day_to_move_to = this.state.s_day_index - 1;
        else if (this.state.s_week_index != min_week_index_this_syllabus){
            week_to_move_to = this.state.s_week_index - 1;
            day_to_move_to = this.state.syllabus.weeks[week_to_move_to].days.length - 1;
        }
        
        this.loadDay(null,week_to_move_to,day_to_move_to, this.state.s_day_in_syllabus - 1);
    }
    
    //Move day from one position to another one
    moveDayToTarget(){
        
        if(this.state.s_week_index != this.state.move_to_week || ( this.state.s_week_index === this.state.move_to_week && this.state.s_day_index != this.state.move_to_day_in_week)){
            let temp_syllabus = Object.assign({}, this.state.syllabus);
            let target_week = temp_syllabus.weeks[this.state.move_to_week].days;
            let day_to_be_moved = temp_syllabus.weeks[this.state.s_week_index].days[this.state.s_day_index];
            target_week.push({});
            
            //when moving within the same week, offsets are required so all elements are moved properly
            let day_offset = this.state.s_week_index === this.state.move_to_week ? (this.state.s_day_index < this.state.move_to_day_in_week ? 1 : 0) : 0;
            let filter_offset = this.state.s_week_index === this.state.move_to_week ? (this.state.s_day_index > this.state.move_to_day_in_week ? 1 : 0) : 0;
            
            //reorganize the new week
            for (let i = target_week.length - (1 + day_offset) ; i > this.state.move_to_day_in_week ; i--){
                target_week[i] = target_week[i-1];
            }
            
            //finally place the day just moved here
            target_week[this.state.move_to_day_in_week + day_offset] = day_to_be_moved;
            
            //remove from previous position
            temp_syllabus.weeks[this.state.s_week_index].days = temp_syllabus.weeks[this.state.s_week_index].days.filter((day,i) => i != (this.state.s_day_index + filter_offset));
            
            
            
            //re-number days
            let new_day_in_syllabus = 0;
            for (let n = 0 ; n <= this.state.move_to_week ; n++ ){
                for (let m = 0 ; m < temp_syllabus.weeks[n].days.length ; m++){
                    new_day_in_syllabus++;
                    if (n == this.state.move_to_week && m == this.state.move_to_day_in_week)
                        break;
                }
            }
            
            //set new state
            this.setState({
                syllabus: temp_syllabus
            },
                ()=> {
                    //hide the Move collapsible box
                    //document.querySelector('#moveDayCollapse').classList.remove('show');
                    
                    this.loadDay(null,this.state.move_to_week,this.state.move_to_day_in_week,new_day_in_syllabus);
                }
            );
        }
    }
    
    //remove day from the week
    removeDay(){
        let temp_state = Object.assign({}, this.state);
        
        temp_state.syllabus.weeks[this.state.s_week_index].days.splice(this.state.s_day_index,1);
        
        //if it is the last day of this week...
        if (this.state.s_day_index == (this.state.syllabus.weeks[this.state.s_week_index].days.length)){
            
            //if there are no more days in this week, remove the week
            if (temp_state.syllabus.weeks[this.state.s_week_index].days.length == 0){
                temp_state.syllabus.weeks.splice(this.state.s_week_index,1);
                temp_state.s_week_index--;
            }
            
            //if there is a week after this one
            if (temp_state.s_week_index < (temp_state.syllabus.weeks.length - 1)){
                temp_state.s_week_index++;
                temp_state.s_day_index = 0;
            }
            else {
                // temp_state.s_week_index--;
                temp_state.s_day_index = temp_state.syllabus.weeks.length ? temp_state.syllabus.weeks[temp_state.s_week_index] ? temp_state.syllabus.weeks[temp_state.s_week_index].days.length - 1 : undefined : undefined;
                temp_state.s_day_in_syllabus--;
            }
        }
        
        temp_state.is_empty = temp_state.syllabus.weeks.length ? false : true;
        this.setState(temp_state, () => {
                                            if (!this.state.is_empty) this.loadDay(null, temp_state.s_week_index, temp_state.s_day_index, temp_state.s_day_in_syllabus);}
                                        );
    }
    
    //insert a new day
    insertDayToTarget(){
        let temp_syllabus = Object.assign({},this.state.syllabus);
        let new_day = {label:'New Day'};
        let week_to_load = this.state.move_to_week;
        let day_to_load = this.state.move_to_day_in_week;
        
        if (this.state.insert_after_selected){
            if (this.state.insert_new_week){
                week_to_load++;
                day_to_load = 0;
            }
            else {
                day_to_load++;
            }
        }
        
        if (this.state.insert_new_week){
            let week = {
                label: 'New Week',
                summary: 'New Week\'s summary',
                topic: 'New Week\'s topic'
            };
            
            week.days = [new_day];
            
            temp_syllabus.weeks.splice(week_to_load, 0, week);
        }
        else{
            temp_syllabus.weeks[week_to_load].days.splice(day_to_load, 0, new_day);
        }
        
        //re-number days
        let day_in_syllabus_to_load = 0;
        for (let n = 0 ; n <= week_to_load ; n++ ){
            for (let m = 0 ; m < temp_syllabus.weeks[n].days.length ; m++){
                day_in_syllabus_to_load++;
                if (n == week_to_load && m == day_to_load)
                    break;
            }
        }
        
        this.setState({
            syllabus:temp_syllabus,
            is_empty: false
        }, () => {
            this.loadDay(null, week_to_load, day_to_load, day_in_syllabus_to_load);
        });
    }
    
    //handle for checkboxes, uses the name prop of the checkbox to identify the field in the state
    handleCheckbox(e){
        const target = e.target;
        const value = target.type === 'checkbox' ? target.checked : target.value;
        const name = target.name;
    
        this.setState({
            [name]: value
        });
    }
    
    
    //Main render method
    render(){
        
        const concepts = this.state['s_key-concepts'].length > 0 ? this.state['s_key-concepts'].map((concept, i) => {
            return <li key={i}>{concept} <button type="button" className="btn btn-sm btn-danger" onClick={(e) => this.deleteFromArray("s_key-concepts",i)}>&times;</button></li>;
            })
            :
            (
                <li className="text-danger">No Concepts in this Day</li>
            );
        
        const lessons = this.state.s_lessons.length > 0 ? this.state.s_lessons.map((lesson, i) => {
            return <li key={i}>{lesson.slug} - {lesson.title} <button type="button" className="btn btn-sm btn-danger" onClick={(e) => this.deleteFromArray("s_lessons",i)}>&times;</button></li>;
            })
            :
            (
                <li className="text-danger">No Lessons in this Day</li>
            );
        
        const quizzes = this.state.s_quizzes.length > 0 ? this.state.s_quizzes.map((quizz, i) => {
            return <li key={i}>{quizz.title} <button type="button" className="btn btn-sm btn-danger" onClick={(e) => this.deleteFromArray("s_quizzes",i)}>&times;</button></li>;
            })
            :
            (
                <li className="text-danger">No Quizzes in this Day</li>
            );
        
        const replits = this.state.s_replits.length > 0 ? this.state.s_replits.map((replit, i) => {
            return <li key={i}>{replit.slug} - {replit.title} <button type="button" className="btn btn-sm btn-danger" onClick={(e) => this.deleteFromArray("s_replits",i)}>&times;</button></li>;
            })
            :
            (
                <li className="text-danger">No Replits in this Day</li>
            );
        
        const assignments = this.state.s_assignments.length > 0 ? this.state.s_assignments.map((assignment, i) => {
            return <li key={i}>{assignment} <button type="button" className="btn btn-sm btn-danger" onClick={(e) => this.deleteFromArray("s_assignments",i)}>&times;</button></li>;
            })
            :
            (
                <li className="text-danger">No Assignments in this Day</li>
            );
        
        const technologies = this.state.s_technologies.length > 0 ? this.state.s_technologies.map((tech, i) => {
            return <li key={i}>{tech} <button type="button" className="btn btn-sm btn-danger" onClick={(e) => this.deleteFromArray("s_technologies",i)}>&times;</button></li>;
            })
            :
            (
                <li className="text-danger">No Technologies in this Day</li>
            );
        
        const weeks_options_list = this.state.syllabus_succeeded && !this.state.is_empty ? this.state.syllabus.weeks.map((week, i) => {
                return <option key={"week" + i} week={i} value={i}>{i+1}</option>;
            })
            :
            (
                <option week="none" value="none">None</option>
            );
            
        const days_in_week_options_list = this.state.syllabus_succeeded && !this.state.is_empty ? this.state.syllabus.weeks[this.state.move_to_week].days.map((day, i) => {
                return <option key={"day" + i} day={i} value={i}>{i+1}</option>;
            })
            :
            (
                <option week="none" value="none">None</option>
            );
        
        
        let days_count = 0;
        const days_all = this.state.syllabus_succeeded && !this.state.is_empty ? this.state.syllabus.weeks.map((week, i) => {
            let days = week.days.map((day, j) => {
                days_count++;
                let this_day_order = days_count;
                return (
                    <li key={"week" + i + "day" + j} className="nav-item text-center"><a className={"nav-link" + (this.state.s_week_index == i && this.state.s_day_index == j ? " active show" : '')} week={i} day={j} onClick={(e) => {this.loadDay(e,i,j, this_day_order);}} data-toggle="tab" href={"week" + i + "day" + j}><strong>{days_count}</strong><br/><small>week {i+1}</small></a></li>
                );
            });
            
            return days;
        }) : '';
        
        
        let day_controls = 's_week_index' in this.state && 's_day_index' in this.state ? (
            <div>
                <button type="button" 
                    className={"btn btn-block" + (this.state.has_changed ? ' btn-warning' : ' btn-outline-success disabled')} 
                    disabled={this.state.has_changed ? undefined : "disabled"}
                    onClick={this.state.has_changed ? (e) => this.saveChanges(e) : undefined}>
                    {this.state.has_changed ? 'SAVE' : 'UP TO DATE'}
                </button>
                
                <button type="button" 
                    className="btn btn-block btn-outline-danger" 
                    onClick={(e) => this.removeDay(e)}>
                    DELETE DAY
                </button>
                
                <button type="button" 
                    className="btn btn-block btn-outline-secondary collapsed" 
                    data-toggle="collapse" data-target="#insertDayCollapse">
                    INSERT DAY
                </button>
                <div className="collapse mt-1" id="insertDayCollapse">
                    <div className="card card-body">
                    
                        <div className="form-group mb-2">
                            <label>Week</label>
                            <select className="form-control form-control-sm" 
                                onChange={(e)=>this.setState({move_to_week:e.target.selectedIndex})} 
                                value={this.state.move_to_week}>
                                {weeks_options_list}
                            </select>
                        </div>
                        <div className="form-check mb-2" style={{fontSize:'0.8em'}}>
                            <input className="form-check-input" type="checkbox" id="insertNewWeek" 
                                name="insert_new_week" 
                                onChange={(e)=>this.handleCheckbox(e)} 
                                checked={this.state.insert_new_week}
                                />
                            <label className="form-check-label" htmlFor="insertNewWeek">
                                Insert New Week
                            </label>
                        </div>
                        
                        <div className="form-group">
                            <label>Day</label>
                            <select className="form-control form-control-sm" onChange={(e)=>this.setState({move_to_day_in_week:e.target.selectedIndex})} value={this.state.move_to_day_in_week}>
                                {days_in_week_options_list}
                            </select>
                        </div>
                        <div className="form-check mb-2" style={{fontSize:'0.8em'}}>
                            <input className="form-check-input" type="checkbox" id="insertAfterSelected" 
                                name="insert_after_selected" 
                                onChange={(e)=>this.handleCheckbox(e)} 
                                checked={this.state.insert_after_selected}
                                />
                            <label className="form-check-label" htmlFor="insertAfterSelected">
                                Insert After selected
                            </label>
                        </div>
                        
                        <button className="btn btn-primary btn-block" type="button" onClick={() => this.insertDayToTarget()}>INSERT</button>
                    </div>
                </div>
                
                <button className="btn btn-outline-secondary btn-block mt-2 collapsed" type="button" data-toggle="collapse" data-target="#moveDayCollapse">
                    MOVE &#x25BE;
                </button>
                <div className="collapse mt-1" id="moveDayCollapse">
                    <div className="card card-body">
                    
                        <div className="form-group">
                            <label>Week</label>
                            <select className="form-control form-control-sm" onChange={(e)=>this.setState({move_to_week:e.target.selectedIndex})} value={this.state.move_to_week}>
                                {weeks_options_list}
                            </select>
                            
                        </div>
                        
                        <div className="form-group">
                            <label>Day</label>
                            <select className="form-control form-control-sm" onChange={(e)=>this.setState({move_to_day_in_week:e.target.selectedIndex})} value={this.state.move_to_day_in_week}>
                                {days_in_week_options_list}
                            </select>
                        </div>
                        
                        <button className="btn btn-primary btn-block" type="button" onClick={() => this.moveDayToTarget()}>MOVE</button>
                    </div>
                </div>
            </div>)
            : 
            '';
        
        
        const day_editor = (this.state.syllabus_succeeded && 's_week_index' in this.state && !this.state.is_empty) ? (<div className={'row' + ('s_day_index' in this.state ? '' : ' d-none')}>
            <div className="col-2">
                <div className="card border-primary mb-3">
                    <div className="card-body">
                        <h3 className="card-title text-primary">Day {this.state.s_day_in_syllabus}</h3>
                        <h5 className="text-primary mb-3">Week {this.state.s_week_index + 1}</h5>
                        
                        <button 
                            className={"btn btn-sm btn-primary day-stepper" + (this.state.s_day_index == 0 && this.state.s_week_index == 0 ? ' disabled' : '')}
                            disabled={this.state.s_day_index == 0 && this.state.s_week_index == 0 ? 'disabled' : undefined}
                            onClick={(e) => this.selectPreviousDay()}
                        >❰</button>
                        <button 
                            className={"btn btn-sm btn-primary day-stepper float-right" + (this.state.s_day_index == this.state.syllabus.weeks[this.state.syllabus.weeks.length - 1].days.length  - 1 && this.state.s_week_index == this.state.syllabus.weeks.length - 1 ? ' disabled' : '')}
                            disabled={this.state.s_day_index == this.state.syllabus.weeks[this.state.syllabus.weeks.length - 1].days.length  - 1 && this.state.s_week_index == this.state.syllabus.weeks.length - 1 ? 'disabled' : undefined}
                            onClick={(e) => this.selectNextDay()}
                        >❱</button>
                    </div>
                    <div className="card-footer border-primary p-3">
                        {day_controls}
                    </div>
                </div>
            </div>
            <div className="col-6">
                <div className="card day-editor bg-light mb-3">
                    <div className="card-header">
                        <h5 className="card-title m-0">Day Information</h5>
                    </div>
                    <div className="card-body">
                        <form onSubmit={this.handleFormSubmit.bind(this)}>
                            
                            <div className="row">
                                <div className="col-12">
                                    <div className="form-group">
                                        <label htmlFor="label_input">Label</label>
                                        <input type="text" className="form-control" id="label_input" onChange={(e) => this.setValue("s_label", e.target.value)} value={this.state.s_label}/>
                                    </div>
                                    <div className="form-group">
                                        <label htmlFor="description_input">Description</label>
                                        <textarea className="form-control" id="description_input" rows="4" onChange={(e) => this.setValue("s_description", e.target.value)} value={this.state.s_description}></textarea>
                                    </div>
                                    <div className="form-group">
                                        <label htmlFor="description_input">Instructions</label>
                                        <textarea className="form-control" id="description_input" rows="6" onChange={(e) => this.setValue("s_instructions", e.target.value)} value={this.state.s_instructions}></textarea>
                                    </div>
                                </div>
                                
                                <div className="col-12">
                                    <div className="form-group">
                                        <label htmlFor="homework_input">Homework</label>
                                        <textarea className="form-control" id="homework_input" rows="3" onChange={(e) => this.setValue("s_homework", e.target.value)} value={this.state.s_homework}></textarea>
                                    </div>
                                    
                                    <div className="form-group">
                                        <label>Project</label>
                                        
                                        <SelectWithAdd 
                                            url="https://assets.breatheco.de/apis/project/all" 
                                            onSelect={this.setValue.bind(this,"s_project")}
                                            fields={["slug","title","status"]} 
                                            valueField="slug" 
                                            listField="title"
                                            filter={["status","published"]}
                                            returnFields={["title","solution","slug"]}
                                            canBeNull={true}
                                            value={this.state.s_project && this.state.s_project.slug ? this.state.s_project.slug : undefined}
                                            />
                                    </div>
                                    
                                    <div className="form-group">
                                        <label>Key Concepts</label>
                                        <ul className="list-unstyled deletable-items">{concepts}</ul>
                                        
                                        <InputWithAdd 
                                            onAdd={this.addToArray.bind(this,"s_key-concepts")} />
                                    </div>
                                    
                                    <div className="form-group">
                                        <label>Lessons</label>
                                        <ul className="list-unstyled deletable-items">{lessons}</ul>
                                        
                                        <SelectWithAdd 
                                            url="https://assets.breatheco.de/apis/lesson/all" 
                                            onAdd={this.addToArray.bind(this,"s_lessons")}
                                            fields={["post_name","post_title","post_status","post_excerpt"]} 
                                            valueField="slug" 
                                            listField="title"
                                            filter={["post_status","publish"]}
                                            translateFields={[["post_name","slug"],["post_title","title"]]}
                                            returnFields={["title","slug"]}/>
                                    </div>
                                </div>
                                
                                <div className="col-12">
                                    <div className="form-group">
                                        <label>Quizzes</label>
                                        <ul className="list-unstyled deletable-items">{quizzes}</ul>
                                        
                                        <SelectWithAdd 
                                            url="https://assets.breatheco.de/apis/quiz/all" 
                                            isObject={true}
                                            onAdd={this.addToArray.bind(this,"s_quizzes")}
                                            fields={["name"]} 
                                            valueField="name" 
                                            listField="name"
                                            translateFields={[["name","title"]]}
                                            returnFields={["title","slug"]}
                                            />
                                    </div>
                                    
                                    <div className="form-group">
                                        <label>Replits</label>
                                        <ul className="list-unstyled deletable-items">{replits}</ul>
                                        
                                        <SelectWithAdd 
                                            url="https://assets.breatheco.de/apis/replit/templates" 
                                            onAdd={this.addToArray.bind(this,"s_replits")}
                                            fields={["slug","title"]} 
                                            valueField="slug" 
                                            listField="title"/>
                                    </div>
                                    
                                    <div className="form-group">
                                        <label>Assignments</label>
                                        <ul className="list-unstyled deletable-items">{assignments}</ul>
                                        
                                        <SelectWithAdd 
                                            url="https://assets.breatheco.de/apis/project/all" 
                                            onAdd={this.addToArray.bind(this,"s_assignments")}
                                            fields={["slug","title","status"]} 
                                            valueField="slug" 
                                            listField="title"
                                            filter={["status","published"]}
                                            returnFields={["slug"]}
                                            returnValueFieldValue={true}/>
                                    </div>
                                    
                                    <div className="form-group">
                                        <label>Technologies</label>
                                        <ul className="list-unstyled deletable-items">{technologies}</ul>
                                        
                                        <InputWithAdd 
                                            onAdd={this.addToArray.bind(this,"s_technologies")} />
                                    </div>
                                </div>
                            </div>
                            
                        </form>
                    </div>
                </div>
            </div>
            <div className="col-4">
                <div className="card day-editor bg-light mb-3">
                    <div className="card-header">
                        <h5 className="card-title m-0">Week <small className="text-secondary float-right">{'s_week_index' in this.state ? this.state.syllabus.weeks[this.state.s_week_index].days.length : 'No'} days</small></h5>
                    </div>
                    <div className="card-body">
                        <div className="form-group">
                            <label htmlFor="week_label_input">Label</label>
                            <textarea className="form-control" id="week_label_input" rows="2" onChange={(e) => this.setValue("s_week_label", e.target.value)} value={this.state.s_week_label}></textarea>
                        </div>
                        <div className="form-group">
                            <label htmlFor="week_topic_input">Topic</label>
                            <textarea className="form-control" id="week_topic_input" rows="2" onChange={(e) => this.setValue("s_week_topic", e.target.value)} value={this.state.s_week_topic}></textarea>
                        </div>
                        <div className="form-group">
                            <label htmlFor="week_summary_input">Summary</label>
                            <textarea className="form-control" id="week_summary_input" rows="5" onChange={(e) => this.setValue("s_week_summary", e.target.value)} value={this.state.s_week_summary}></textarea>
                        </div>
                        <div className="form-group">
                            <label htmlFor="week_warning_input">Warning</label>
                            <textarea className="form-control" id="week_warning_input" rows="5" onChange={(e) => this.setValue("s_week_warning", e.target.value)} value={this.state.s_week_warning}></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>) : this.state.is_empty && this.state.syllabus_succeeded ? (
            <div className="row"><div className="col-12 mt-3">
                <div className="card mb-3">
                    <div className="card-body">
                        <p>It looks like the Syllabus is empty...</p>
                        
                        <button type="button" className="btn btn-block btn-outline-primary" onClick={(e) => {
                                                                                                                this.setState({
                                                                                                                    insert_new_week :true,
                                                                                                                    insert_after_selected: false,
                                                                                                                    move_to_week: 0,
                                                                                                                    move_to_day_in_week: 0,
                                                                                                                    s_week_index: 0,
                                                                                                                    s_day_index: 0
                                                                                                                }, () => this.insertDayToTarget());
                                                                                                                
                                                                                                            }}>INSERT DAY</button>
                    </div>
                </div>
            </div></div>
            ) : '';
        
        
        return (
            
            <div>
                {this.state.syllabus_succeeded ? (
                    <div className="">
                        <div className="container-fluid p-0 bg-light bordered-bottom">
                            <div className="container">
                                <div className="row">
                                    <div className="col-12 py-3">
                                        <h3 className="display-4 mb-4">Syllabus</h3>
                                        
                                        <div className="row">
                                            <div className="col-4">
                                                <div className="form-group">
                                                    <label htmlFor="label_input">Label</label>
                                                    <input type="text" className="form-control" id="syllabus_label_input" onChange={(evt) => this.handleSyllabus('label',evt)} value={this.state.syllabus.label}/>
                                                </div>
                                            </div>
                                            <div className="col-4">
                                                <div className="form-group">
                                                    <label htmlFor="syllabus_profile_input">Profile</label>
                                                    <input type="text" className="form-control" id="syllabus_profile_input" onChange={(evt) => this.handleSyllabus('profile',evt)} value={this.state.syllabus.profile}/>
                                                </div>
                                            </div>
                                            <div className="col-4">
                                                <div className="form-group">
                                                    <label>&nbsp;</label>
                                                    <button type="button" onClick={(e) => this.realSubmit()} className="btn btn-primary btn-block">Download JSON</button>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                        { !this.state.is_empty ? (
                            <div className="container">
                                <div className="row">
                                    <div className="col-12 py-3 mb-3">
                                        <h5 className="card-title">Days</h5>
                                        <div className="scroller-frame">
                                            <ul className="nav nav-pills flex-nowrap day-scroller mx-4" id="scrollme">
                                                {days_all}
                                            </ul>
                                            <span className="left-scroller" onClick={(e) => e.target.parentNode.children[0].scrollLeft -= e.target.parentNode.children[0].offsetWidth / 2}>&#x2770;</span>
                                            <span className="right-scroller" onClick={(e) => e.target.parentNode.children[0].scrollLeft += e.target.parentNode.children[0].offsetWidth / 2}>&#x2771;</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        ) : (
                            ''
                        )}
                        <div className="container">
                            {day_editor}
                        </div>
                        <div className="container">
                            <div className="col-12 mt-5 json-output"></div>
                        </div>
                        
                    </div>
                    
                ) : (
                    <div className="container">
                        <div className="row">
                            <div className="col my-4">
                                <div className="card border-primary">
                                    <div className="card-body">
                                        <h5 className="card-title">Get Syllabus</h5>
                                        <div className="form-group">
                                            <input type="text" className="form-control" id="syllabus_url" defaultValue="https://assets.breatheco.de/apis/syllabus/full-stack"/>
                                        </div>
                                        <button type="button" className="btn btn-primary" onClick={(e) => this.pullSyllabus()}>PULL</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                )}
                {//this.state.delete_modal_show ? <Modal show={true} onHide={console.log('Hide')} /> :
                ''}
            </div>
        );
    }
}


//create your first component
export class Home extends React.Component{
    render(){
        return (
            <div className="">
                <CreateCard />
            </div>
        );
    }
}

