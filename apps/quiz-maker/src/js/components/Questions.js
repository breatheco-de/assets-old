import React from 'react';

export default class Questions extends React.Component {
	constructor(props){
		super(props);
		this.state = {
			valueQuestion: [],
			valueOption: [],
			isChecked: false,
			showMessage: false,
			editing: false
		}

		this.handleChangeOptions = this.handleChangeOptions.bind(this)
	}

	componentWillReceiveProps(nextProps){
		this.setState({
			valueQuestion: nextProps.data.q,
			valueOption: nextProps.answer
		});
	}

	componentWillMount(){
		this.setState({
			valueQuestion: this.props.data.q,
			valueOption: this.props.answer
		});
	}

	handleChangeQuestion(event){
		this.setState({
			valueQuestion: event.target.value
		});
		this.props.handleJsonQuestion(event.target.value, {typeRequest: 'question'});
	}

	handleChangeOptions(event, key){
		const newOptions = this.state.valueOption.map((opt, i)=> {
			if(key == i){
				return {option: event.target.value, correct: opt.correct}
			}else{
				return opt;
			}
		});
		this.setState({
			valueOption: newOptions
		});
		this.props.handleJsonQuestion(newOptions, {typeRequest: 'option'}, key);
	}

	handleChangeOptionsTrueFalse(event, key){
		this.setState({
			isChecked: !this.state.isChecked
		});
		if(this.state.isChecked){
			const newOptions = this.state.valueOption.map((opt, i)=> {
				if(key == i){
					return {option: opt.option, correct: true};
				}else{
					return opt;
				}
			});
			this.setState({
				valueOption: newOptions
			});
			this.props.handleJsonQuestion(newOptions, {typeRequest: 'checkbox'});
		}else{
			const newOptions = this.state.valueOption.map((opt, i)=> {
				if(key == i){
					return {option: opt.option, correct: false}
				}else{
					return opt;
				}
			});
			this.setState({
				valueOption: newOptions
			});
			this.props.handleJsonQuestion(newOptions, {typeRequest: 'checkbox'});
		}
	}

	handleDeleteQuestion(){
		this.setState({
			showMessage: true,
		});
		setTimeout(()=>{ 
			this.setState({
				showMessage: false,
			}); }, 2000);
		this.props.onSelect();
	}

	handleDeleteOpcion(data, key){
		this.props.onDeleteOption(data, key);
	}

	handleNewOption(){
		this.props.onNewOption();
	}

	handleUpQuestion(){
		this.props.onUpQuestion();
	}

	handleDownQuestion(){
		this.props.onDownQuestion();
	}

	render () {
		let options = null;
		options = this.state.valueOption.map((value, key) =>
			(<div className="row option" key={key}>
				<div className="col-8">
					<input 
						className="form-control" 
						type="text"
						value={value.option} 
						onChange={(event) => this.handleChangeOptions(event, key)}
						placeholder="Type your option value"
					/>
				</div>
				<div className="col-2">
					<label className="float-right">
						Correct?
					</label>
					<input 
						type="checkbox" 
						checked={value.correct}
						value={value.correct}
						onChange={(event) => this.handleChangeOptionsTrueFalse(event, key)}
					/>
				</div>
				<div className="col-2">
					<button className="btn text-danger" onClick={() => this.handleDeleteOpcion(value, key)}>
						<i className="fas fa-trash-alt text-danger"></i>
					</button>
				</div>
			</div>));

		return (
			<div className="questions">
				<div className={"message-delete " + (this.state.showMessage ? "show " : "hidden")}>
					<div className="alert alert-danger" role="alert">
						Question Deleted
					</div>
				</div>
				<button 
					type="button" 
					className="btn text-danger float-right" 
					onClick={this.handleDeleteQuestion.bind(this)}>delete question
				</button>
				<button className={"btn text-primary " + (this.props.firstQuestion ? "hidden" : "")} onClick={()=>this.handleUpQuestion()}>
					<i class="fas fa-arrow-circle-up"></i>
				</button>
				<button className={"btn text-primary " + (this.props.lastQuestion ? "hidden" : "")} onClick={()=>this.handleDownQuestion()}>
					<i class="fas fa-arrow-circle-down"></i>
				</button>
				<div className="form-group">
					<input
						className="form-control"
						type="text"
						value={this.state.valueQuestion}
						onChange={(event) => this.handleChangeQuestion(event)}
						placeholder="Type your question title"
					/>
				</div>
				{options}
				<div className="row">
					<div className="col-12">
						<button className="btn text-primary" onClick={() => this.handleNewOption()}>
							<i className="fas fa-plus-circle"></i> new option
						</button>
					</div>
				</div>
            </div>
		);
	}
}