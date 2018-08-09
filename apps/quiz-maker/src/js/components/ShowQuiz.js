import React from 'react';
import Info from './Info';
import Questions from './Questions';
import NewQuestion from './NewQuestion';

export default class ShowQuiz extends React.Component {
	constructor(props){
		super(props);
		this.state = {
			apiBadges: [],
			apiInfo: [],
			apiQuestions: [],
			json: []
		}
	}

	componentWillMount(){
		this.getApi();
	}

	//Consulta API
	getApi(){
		fetch(this.props.data)
		.then(function(response) {
			return response.json();
		})
		.then((data) => {
			this.setState({
				apiInfo: data.info,
				apiQuestions: data.questions,
				apiBadges: data.info.badges,
			});
		})
		.catch(function(error){
			console.log('error', error);
		})
	}

	//Agregar pregunta
	handleNewQuestion(){
		const newQuestion = {
			q: '',
			a: [
				{
					option: '',
					correct: false
				},
				{
					option: '',
					correct: false
				},
				{
					option: '',
					correct: false
				}
			]
		}
		this.setState({
			apiQuestions: [...this.state.apiQuestions, newQuestion]
		});
		setTimeout(() => window.scrollTo(0,document.body.scrollHeight), 500);
		
	}

	//Eliminar pregunta
	handleDeleteQuestion(data, i){
		let filteredQuestions = this.state.apiQuestions.filter(el => el != data );
		this.setState(({
			apiQuestions: filteredQuestions
		}));
	}

	//Eliminar option de pregunta
	handleDeleteOption(data, key, idOption){
		let filteredQuestions = this.state.apiQuestions.map((value, i)=>{
			if(key == i){
				value.a.splice(idOption, 1)
				return ({q: value.q, a: value.a})
			}else{
				return ({q: value.q, a: value.a})
			}
		});

		this.setState(({
			apiQuestions: filteredQuestions
		}));
	}

	//Agregar nueva opcion a pregunta
	handleNewOption(key){
		let resultNewOption = this.state.apiQuestions.map((value, i) =>{
			if(key == i){
				value.a.push({option:'', correct: false});
				return ({q: value.q, a: value.a})
			}else{
				return({q: value.q, a: value.a})
			}
		});

		this.setState({
			apiQuestions: resultNewOption
		});
	}

	handleUpQuestion(key){
		const arr = this.state.apiQuestions;
		const old_index = key;
		const new_index = key-1;
		//this.handleActionUpQuestion(this.state.apiQuestions, old_index, new_index);
		if (new_index >= arr.length) {
			var k = new_index - arr.length + 1;
			while (k--) {
				arr.push(undefined);
			}
		}
		arr.splice(new_index, 0, arr.splice(old_index, 1)[0]);

		this.setState({
			apiQuestions: arr
		})
	}

	handleDownQuestion(key){
		const arr = this.state.apiQuestions;
		const old_index = key;
		const new_index = key+1;
		if (new_index >= arr.length) {
			var k = new_index - arr.length + 1;
			while (k--) {
				arr.push(undefined);
			}
		}
		arr.splice(new_index, 0, arr.splice(old_index, 1)[0]);

		this.setState({
			apiQuestions: arr
		})
	}
	
	//Datos que recibo para modificar el json del API
	getValueFromQuestion(data, type, idQuestion, idOption){
		if(type.typeRequest == 'question'){
			let resultQuestions = this.state.apiQuestions.map((value, i) =>{
				if(idQuestion == i){
					return {q: data, a: value.a}
				}else{
					return {q: value.q, a: value.a}
				}
			});
			this.setState({
				apiQuestions: resultQuestions
			});
		}else if(type.typeRequest == 'option'){
			let resultOption = this.state.apiQuestions.map((value, idQ) =>{
				if(idQuestion == idQ){
					return({q: value.q, a: data})
				}else{
					return({q: value.q, a: value.a})
				}
			});
			this.setState({
				apiQuestions: resultOption
			});
		}else if(type.typeRequest == 'checkbox'){
			let resultCheckbox = this.state.apiQuestions.map((value, idQ) =>{
				if(idQuestion == idQ){
					return({q: value.q, a: data})
				}else{
					return({q: value.q, a: value.a})
				}
			});
			this.setState({
				apiQuestions: resultCheckbox
			});
		}
	}

	getValueFromInfo(data, type){
		if(type.typeRequest == 'nameBadges' || type.typeRequest == 'pointBadges' || 
			type.typeRequest == 'nameSlug' || type.typeRequest == 'nameResult' ||
			type.typeRequest == 'nameMain' || type.typeRequest == 'name'){
			this.setState({
				apiInfo: data
			})
		}
	}

	download(filename) {
		const buildJson = {
			info: {
				name: this.state.apiInfo.name,
				main: this.state.apiInfo.main,
				results: this.state.apiInfo.results,
				badges: this.state.apiInfo.badges,
				slug: this.state.apiInfo.slug
			},
			questions: this.state.apiQuestions
		}
		
		const text = JSON.stringify(buildJson);
		var element = document.createElement('a');
		element.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(text));
		element.setAttribute('download', filename);
		element.style.display = 'none';
		document.body.appendChild(element);
		element.click();
		document.body.removeChild(element);
	}

	render () {
		
		const results = this.state.apiQuestions.map((value, key) => {
							const lastQuestion = this.state.apiQuestions.length - 1;
							if(key == 0){
								return (
									<Questions 
									key={key} 
									data={value}
									answer={value.a}
									handleJsonQuestion={(data, type, idOption)=>this.getValueFromQuestion(data, type, key, idOption)}
									onSelect={()=>this.handleDeleteQuestion(value, key)}
									onDeleteOption={(value, idOption)=>this.handleDeleteOption(value, key, idOption)}
									onNewOption={()=>this.handleNewOption(key)}
									onUpQuestion={()=>this.handleUpQuestion(key)}
									onDownQuestion={()=>this.handleDownQuestion(key)}
									firstQuestion={true}/>
								)
							}else if(key<lastQuestion){
								return (
									<Questions 
									key={key} 
									data={value}
									answer={value.a}
									handleJsonQuestion={(data, type, idOption)=>this.getValueFromQuestion(data, type, key, idOption)}
									onSelect={()=>this.handleDeleteQuestion(value, key)}
									onDeleteOption={(value, idOption)=>this.handleDeleteOption(value, key, idOption)}
									onNewOption={()=>this.handleNewOption(key)}
									onUpQuestion={()=>this.handleUpQuestion(key)}
									onDownQuestion={()=>this.handleDownQuestion(key)}/>
								)
							}else{
								return (
									<Questions 
									key={key} 
									data={value}
									answer={value.a}
									handleJsonQuestion={(data, type, idOption)=>this.getValueFromQuestion(data, type, key, idOption)}
									onSelect={()=>this.handleDeleteQuestion(value, key)}
									onDeleteOption={(value, idOption)=>this.handleDeleteOption(value, key, idOption)}
									onNewOption={()=>this.handleNewOption(key)}
									onUpQuestion={()=>this.handleUpQuestion(key)}
									onDownQuestion={()=>this.handleDownQuestion(key)}
									lastQuestion={true}/>
								)
							}
					});
		return (
			<div className="container-fluid p-0">
				<button className="btn btn-primary download-btn" onClick={()=>this.download("quiz.json")}>
					<i className="fas fa-download"></i> download progress
				</button>
    			<nav className="navbar navbar-dark bg-dark">
    				<a className="navbar-brand" href="#">
    					General Quiz Information
    				</a>
    			</nav>
				<Info data={this.state.apiInfo} handleJsonInfo={(data, type) => this.getValueFromInfo(data, type)}/>
    			<nav className="questions-nav navbar sticky-top navbar-dark bg-dark">
    				<a className="navbar-brand" href="#">
    					Questions
    				</a>
    				<div className="ml-auto">
    					<NewQuestion onClick={this.handleNewQuestion.bind(this)}/>
    				</div>
    			</nav>
                <div className="row">
                    <div className="col-12 col-sm-10 col-md-8 col-xl-6 mx-auto">
						{results}
                    </div>
                </div>
            </div>
		);
	}
}