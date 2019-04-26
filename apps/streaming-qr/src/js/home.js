import React from "react";
import PropTypes from "prop-types";
import { Notifier, Notify } from "bc-react-notifier";
import QRCode from "qrcode.react";
function getUrlParameter(name) {
	var params = new URLSearchParams(window.location.search);
	return params.has(name) ? params.get(name) : null;
}
const HOST = "https://assets.breatheco.de/apis/streaming";
export class Home extends React.Component {
	constructor(props) {
		super(props);
		this.state = {
			showQr: true,
			data: [],
			cohorts: [],
			cohort: getUrlParameter("cohort"),
			isLoaded: false,
			qrCode: {
				ssid: "",
				pwd: "",
				res: "1080p",
				rate: "3",
				dur: "0",
				url: ""
			},
			selectedCohort: null
		};
	}

	componentDidMount() {
		console.log("Params: ", this.state.params);
		if (this.state.cohort)
			fetch(HOST + "/cohort/" + this.state.cohort)
				.then(res => {
					if (res.status == 200) return res.json();
					this.setState({ cohort: null });
					throw new Error("There was a problem finding the cohort");
				})
				.then(json => {
					json.name = this.state.cohort;
					this.setState({
						isLoaded: true,
						selectedCohort: json,
						qrCode: Object.assign(this.state.qrCode, {
							url: json.rtmp
						})
					});
				})
				.catch(err => Notify.error(err.message || "There was a problem"));

		fetch("https://api.breatheco.de/cohorts/")
			.then(res => res.json())
			.then(json => {
				this.setState({
					cohorts: json.data || json,
					qrCode: Object.assign(this.state.qrCode, { url: json.rtmp })
				});
			})
			.catch(err => Notify.error(err.message || "There was a problem"));
	}
	changeHandler = e => {
		this.setState({
			qrCode: Object.assign(this.state.qrCode, { ssid: e.target.value })
		});
	};
	changeHandlerPwd = e => {
		this.setState({
			qrCode: Object.assign(this.state.qrCode, { pwd: e.target.value })
		});
	};

	handleSubmit = e => {
		if (!this.state.qrCode.pwd || this.state.qrCode.pwd.length == 0) Notify.error("The network password is empty");
		else if (!this.state.qrCode.ssid || this.state.qrCode.ssid.length == 0)
			Notify.error("The network name is empty");
		else this.setState({ showQr: false });
	};
	render() {
		let inputResult;
		let { cohorts } = this.state;

		if (!this.state.cohort) {
			inputResult = (
				<div className="form-group">
					<label htmlFor="formGroupExampleInput2">Select Cohort</label>
					<select
						onChange={e => {
							const cohort = cohorts.find(c => c.slug == e.target.value);
							this.setState({ selectedCohort: cohort });
							fetch(HOST + "/cohort/" + e.target.value)
								.then(res => {
									if (res.status == 200) return res.json();
									else
										throw new Error("There was a problem loading the cohort streaming information");
								})
								.then(streamingJson => {
									this.setState({
										isLoaded: true,
										data: streamingJson,
										qrCode: Object.assign(this.state.qrCode, { url: streamingJson.rtmp })
									});
								})
								.catch(err => Notify.error(err.message || "There was a problem"));
						}}
						className="custom-select custom-select-md mb-3">
						<option defaultValue>Open this select menu</option>
						{cohorts.map((item, index) => {
							return (
								<option key={index} value={item.slug}>
									{item.name}
								</option>
							);
						})}
					</select>
				</div>
			);
		}
		return (
			<div id="box" className="w-50 d-flex justify-content-center mx-auto text-center align-self-center h-50">
				<Notifier />
				{this.state.showQr ? (
					<div className="align-self-bottom border-info mt-5">
						<div className="form-group">
							<label htmlFor="formGroupExampleInput">Wi-Fi Name (SSID)</label>
							<input
								type="text"
								value={this.state.qrCode.ssid}
								className="form-control"
								id="formGroupExampleInput"
								placeholder="Type your wifi name"
								onChange={this.changeHandler}
							/>
						</div>
						<div className="form-group">
							<label htmlFor="formGroupExampleInput2">Wi-Fi Password</label>
							<input
								type="text"
								className="form-control"
								id="formGroupExampleInput2"
								placeholder="Type your wifi password"
								value={this.state.qrCode.pwd}
								onChange={this.changeHandlerPwd}
							/>
						</div>
						{inputResult}
						{this.state.isLoaded && (
							<button type="button" className="btn btn-success form-control" onClick={this.handleSubmit}>
								Generate QR
							</button>
						)}
					</div>
				) : (
					<div className="align-self-center border-info">
						<h3>{this.state.selectedCohort.name}</h3>
						<QRCode value={JSON.stringify(this.state.qrCode)} size="200" />
						<p className="mt-3">
							<button type="button" className="btn btn-light" onClick={() => window.location.reload()}>
								Clear
							</button>
							<button
								type="button"
								className="btn btn-light ml-3"
								onClick={() => window.open(this.state.selectedCohort.iframe)}>
								Watch Live
							</button>
						</p>
					</div>
				)}
			</div>
		);
	}
}

Home.propTypes = {
	match: PropTypes.object,
	params: PropTypes.object
};
